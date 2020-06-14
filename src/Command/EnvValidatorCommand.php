<?php


namespace Selevia\EnvValidator\Command;


use Selevia\EnvValidator\Validator\Exception\FileNotFoundException;
use Selevia\EnvValidator\Validator\Exception\InvalidFormatException;
use Selevia\EnvValidator\Validator\Result\VarResult;
use Selevia\EnvValidator\Validator\Status\Status;
use Selevia\EnvValidator\Validator\Validator;
use Selevia\EnvValidator\Validator\ValidatorFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EnvValidatorCommand extends Command
{

    public const COMMAND_NAME = 'env_validator:validate';
    public const COMMAND_DESCRIPTION = 'Validate env variables';
    public const COMMAND_HELP = 'The Validator will load the variables, validate them, and provide a summary of the results';

    protected const OPTION_ACTUAL = 'actual';
    protected const OPTION_EXPECTED = 'expected';
    protected const DEFAULT_ACTUAL_ENV_FILE = '.env';
    protected const DEFAULT_EXPECTED_ENV_FILE = '.env.example';


    /**
     * @var ValidatorFactory
     */
    protected $validatorFactory;

    public function __construct(ValidatorFactory $validatorFactory)
    {
        parent::__construct();

        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this->setName(self::COMMAND_NAME);

        $this->setDescription(self::COMMAND_DESCRIPTION);
        $this->setHelp(self::COMMAND_HELP);

        $this->addOption(
            self::OPTION_ACTUAL,
            'a',
            InputOption::VALUE_REQUIRED,
            'Actual environment filename',
            self::DEFAULT_ACTUAL_ENV_FILE
        );

        $this->addOption(
            self::OPTION_EXPECTED,
            'e',
            InputOption::VALUE_REQUIRED,
            'Expected environment filename',
            self::DEFAULT_EXPECTED_ENV_FILE
        );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $validator = $this->createValidator($input);

        try {
            $result = $validator->validate();
        } catch (FileNotFoundException|InvalidFormatException $e) {
            $io->error($e->getMessage());

            return self::FAILURE;
        }

        $successResults = $result->listVarResults(Status::TYPE_SUCCESS);
        $warningResults = $result->listVarResults(Status::TYPE_WARNING);
        $errorResults = $result->listVarResults(Status::TYPE_ERROR);

        $this->printTitle(count($successResults), count($warningResults), count($errorResults), $io);
        $this->printMessages($errorResults, $warningResults, $io);

        return self::SUCCESS;
    }

    /**
     * Create Validator for provided input
     *
     * @param InputInterface $input
     *
     * @return Validator
     */
    protected function createValidator(InputInterface $input): Validator
    {
        $actual = $input->getOption(self::OPTION_ACTUAL);
        $expected = $input->getOption(self::OPTION_EXPECTED);

        return $this->getValidatorFactory()->createForFilenames($actual, $expected);
    }

    /**
     * @param int          $successCount
     * @param int          $warningCount
     * @param int          $errorCount
     * @param SymfonyStyle $io
     */
    protected function printTitle(int $successCount, int $warningCount, int $errorCount, SymfonyStyle $io): void
    {
        $titleParts = [
            sprintf('<fg=%s>Success: %d</>', 'green', $successCount),
            sprintf('<fg=%s>Warning: %d</>', 'yellow', $warningCount),
            sprintf('<fg=%s>Errors: %d</>', 'red', $errorCount),
        ];

        $io->title(implode(', ', $titleParts));
    }

    /**
     * @param VarResult[]  $errorResults
     * @param VarResult[]  $warningResults
     * @param SymfonyStyle $io
     */
    protected function printMessages(array $errorResults, array $warningResults, SymfonyStyle $io): void
    {
        foreach ($errorResults as $errorResult) {
            $io->writeln(sprintf('<fg=%s>%s</>', 'red', $errorResult->createMessage()));
        }
        foreach ($warningResults as $warningResult) {
            $io->writeln(sprintf('<fg=%s>%s</>', 'yellow', $warningResult->createMessage()));
        }
    }

    /**
     * @return ValidatorFactory
     */
    protected function getValidatorFactory(): ValidatorFactory
    {
        return $this->validatorFactory;
    }
}