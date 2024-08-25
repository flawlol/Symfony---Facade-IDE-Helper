<?php

namespace Flawlol\FacadeIdeHelper\Service;

use Flawlol\FacadeIdeHelper\Interface\FacadeHelperGeneratorInterface;
use Flawlol\Facade\Abstract\Facade;
use Psr\Container\ContainerInterface;
use ReflectionParameter;
use SplFileObject;
use Symfony\Component\Finder\Finder;

/**
 * Class FacadeHelperGenerator
 *
 * This class is responsible for generating facade helper files for IDEs.
 *
 * @author Flawlol - Norbert Kecső
 */
final class FacadeHelperGenerator implements FacadeHelperGeneratorInterface
{
    /**
     * @var string The current namespace being processed.
     */
    private string $currentNamespace = '';

    /**
     * FacadeHelperGenerator constructor.
     *
     * @param ContainerInterface $container The container interface for dependency injection.
     */
    public function __construct(private readonly ContainerInterface $container)
    {

    }

    /**
     * Generate the facade helper file.
     *
     * This method scans the source directory for PHP files, identifies classes that extend the Facade class,
     * and writes their method signatures to a helper file for IDE autocompletion.
     */
    public function generate(): void
    {
        $projectDir = $this->container->getParameter('kernel.project_dir');
        $srcDir = $projectDir . '/src';

        $finder = new Finder();
        $finder->files()->in($srcDir)->name('*.php');

        $helperFile = new SplFileObject('_ide-helper.php', 'w');
        $helperFile->fwrite("<?php\n");
        $helperFile->fwrite("\n");

        foreach ($finder as $file) {
            $className = $this->getClassNameFromFile($file->getRealPath());

            if (!class_exists($className)) {
                continue;
            }

            $reflectionClass = new \ReflectionClass($className);

            if ($reflectionClass->isAbstract() || $reflectionClass->isInterface()) {
                continue;
            }

            $object = $reflectionClass->newInstanceWithoutConstructor();

            if ($this->isInstanceOfWithoutInitializing($object)) {
                $this->writeNamespaceAndClass($helperFile, $reflectionClass, $object);
            }
        }

        if ($this->currentNamespace !== '') {
            $helperFile->fwrite("}\n\n");
        }
    }

    /**
     * Get the fully qualified class name from a file.
     *
     * @param string $filePath The path to the file.
     * @return string|null The fully qualified class name, or null if not found.
     */
    private function getClassNameFromFile(string $filePath): ?string
    {
        $contents = file_get_contents($filePath);
        $namespace = '';

        if (preg_match('/namespace\s+(.+?);/', $contents, $matches)) {
            $namespace = $matches[1];
        }

        if (preg_match('/class\s+(\w+)/', $contents, $matches)) {
            return $namespace ? $namespace . '\\' . $matches[1] : $matches[1];
        }

        return null;
    }

    /**
     * Check if a class is an instance of the Facade class without initializing it.
     *
     * @param object|null $className The class to check.
     * @return bool True if the class is an instance of the Facade class, false otherwise.
     */
    private function isInstanceOfWithoutInitializing(object $className = null): bool
    {
        return is_subclass_of($className, Facade::class) || in_array(Facade::class, class_implements($className));
    }

    /**
     * Write the namespace and class structure to the helper file.
     *
     * @param SplFileObject $helperFile The helper file object.
     * @param \ReflectionClass $reflectionClass The reflection class object.
     * @param object $object The class object.
     */
    private function writeNamespaceAndClass(SplFileObject $helperFile, \ReflectionClass $reflectionClass, $object): void
    {
        $namespace = $reflectionClass->getNamespaceName();

        if ($namespace !== $this->currentNamespace) {

            if ($this->currentNamespace !== '') {
                $helperFile->fwrite("}\n\n");
            }

            $this->currentNamespace = $namespace;
            $helperFile->fwrite("namespace {$namespace} {\n");
            $helperFile->fwrite("\n");
        }

        $helperFile->fwrite("    class {$reflectionClass->getShortName()}\n");
        $helperFile->fwrite("    {\n");

        $service = $object::getFacadeAccessor();
        $serviceInstance = $this->container->get($service);
        $serviceInstanceReflection = new \ReflectionClass($serviceInstance);
        $methods = $serviceInstanceReflection->getMethods();

        foreach ($methods as $method) {

            if ($method->isPublic() && !$method->isStatic()) {
                $this->writeMethod($helperFile, $method, $serviceInstanceReflection);
            }
        }

        $helperFile->fwrite("    }\n");
    }

    /**
     * Write the method signature and docblock to the helper file.
     *
     * @param SplFileObject $helperFile The helper file object.
     * @param \ReflectionMethod $method The reflection method object.
     * @param \ReflectionClass $serviceInstanceReflection The reflection class object of the service instance.
     */
    private function writeMethod(SplFileObject $helperFile, \ReflectionMethod $method, \ReflectionClass $serviceInstanceReflection): void
    {
        $params = [];

        foreach ($method->getParameters() as $param) {
            $paramType = $param->getType();
            $type = $paramType ? $paramType->getName() . ' ' : '';
            $defaultValue = $param->isOptional() ? ' = ' . var_export($param->getDefaultValue(), true) : '';
            $params[] = $type . '$' . $param->getName() . $defaultValue;
        }

        $paramsStringOriginal = implode(', ', $params);

        $paramsVariable = $params ? '$' . implode(', $', array_map(
            static fn(ReflectionParameter $param): string => $param->getName(),
            $method->getParameters())
            ) : '';

        $returnType = $method->getReturnType();
        $returnTypeString = $returnType ? $returnType->getName() : 'mixed';

        if ($returnTypeString === 'self') {
            $returnTypeString = "\\" . $serviceInstanceReflection->getName();
        }

        $params = [];

        foreach ($method->getParameters() as $param) {
            $paramType = $param->getType();
            $type = $paramType ? $paramType->getName() . ' ' : '';
            $params[] = "@param {$type}\${$param->getName()}";
        }

        $paramsString = implode("\n         * ", $params);

        $helperFile->fwrite("        /**\n");

        if ($paramsString) {
            $helperFile->fwrite("         * {$paramsString}\n");
        }
        $helperFile->fwrite("         * @return {$returnTypeString}\n");
        $helperFile->fwrite("         */\n");

        $helperFile->fwrite("        public static function {$method->getName()}($paramsStringOriginal): $returnTypeString\n");
        $helperFile->fwrite("        {\n");
        $helperFile->fwrite("            /** @var \\{$serviceInstanceReflection->getName()} \$instance */\n");
        $helperFile->fwrite("            return \$instance->{$method->getName()}($paramsVariable);\n");
        $helperFile->fwrite("        }\n");
    }
}