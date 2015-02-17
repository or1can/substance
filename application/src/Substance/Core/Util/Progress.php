<?php
/* Substance - Content Management System and application framework.
 * Copyright (C) 2015 Kevin Rogers
 *
 * Substance is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Substance is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Substance.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Substance\Core\Util;

use Substance\Core\Presentation\Elements\Select;

/**
 * Represents the progress of a job.
 *
 * Progress is measured as a number between 0 and 1, where 0 means a job has
 * just started and 1 means it is complete.
 */
interface Progress {

  /**
   * Returns the current progress.
   *
   * @return float a float between 0 and 1, where 0 means just started and 1
   * means complete.
   */
  public function getProgress();

}
