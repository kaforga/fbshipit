<?hh // strict
/**
 * Copyright (c) 2016-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */
namespace Facebook\ShipIt;

require_once(__DIR__.'/ShipItCLIArgument.php');

abstract class ShipItPhase {
  private bool $skipped = false;

  abstract public function getReadableName(): string;
  abstract protected function runImpl(ShipItBaseConfig $config): void;

  /**
   * This allows you to build multi-project automation.
   *
   * It gives you a guarantee that your generic tooling is only going to do
   * generic things.
   *
   * For example, Facebook will be using this to automatically test that diffs
   * don't break the push by running with --skip-push --skip-project-specific;
   * some projects have custom build and test phases that aren't relevant,
   * others do secondary pushes to an internal mirror or public gh-pages
   * branches which would be undesired and harmful in this context.
   */
  abstract protected function isProjectSpecific(): bool;

  public function getCLIArguments(): ImmVector<ShipItCLIArgument> {
    return ImmVector { };
  }

  final public function isSkipped(): bool {
    return $this->skipped;
  }

  final protected function skip(): void {
    $this->skipped = true;
  }

  final protected function unskip(): void {
    $this->skipped = false;
  }

  final public function run(ShipItBaseConfig $config): void {
    if (
      $this->isProjectSpecific()
      && !$config->areProjectSpecificPhasesEnabled()
    ) {
      $this->skip();
    }

    if ($this->isSkipped()) {
      printf("Skipping phase: %s\n", $this->getReadableName());
      return;
    }
    printf("Starting phase: %s\n", $this->getReadableName());
    $this->runImpl($config);
    printf("Finished phase: %s\n", $this->getReadableName());
  }
}
