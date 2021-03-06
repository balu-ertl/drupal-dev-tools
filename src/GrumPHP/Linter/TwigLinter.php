<?php

namespace Brainsum\DrupalDevTools\GrumPHP\Linter;

use Brainsum\DrupalDevTools\TwigLinter\SimpleString;
use GrumPHP\Collection\LintErrorsCollection;
use GrumPHP\Linter\LinterInterface;
use Brainsum\DrupalDevTools\TwigLinter\LintCommand;

/**
 * Twig linter service for GrumPHP.
 *
 * @package Brainsum\DrupalDevTools\GrumPHP\Linter
 */
class TwigLinter implements LinterInterface {

  protected $linter;

  /**
   * TwigLinter constructor.
   */
  public function __construct() {
    $this->linter = new LintCommand();
  }

  /**
   * {@inheritdoc}
   */
  public function lint(\SplFileInfo $file): LintErrorsCollection {
    $errors = new LintErrorsCollection();

    $lintResults = [];

    try {
      $lintResults = $this->linter->run($file->getPathname());
    }
    catch (\Exception $exception) {
      // @todo: What to do with this?
      $errors->add(new SimpleString($exception->getMessage()));
    }

    if ($lintResults['failed'] > 0) {
      foreach ($lintResults['errors'] as $lintError) {
        $errors->add(new SimpleString($lintError));
      }
    }

    return $errors;
  }

  /**
   * {@inheritdoc}
   */
  public function isInstalled(): bool {
    return \class_exists(LintCommand::class);
  }

}
