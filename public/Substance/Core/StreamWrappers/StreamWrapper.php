<?php
/* Substance - Content Management System and application framework.
 * Copyright (C) 2014 Kevin Rogers
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

namespace Substance\Core\StreamWrappers;

/**
 * Prototype StreamWrapper class.
 */
interface StreamWrapper {

  public $context;

  /**
   * @return bool
   */
  public function dir_closedir();

  /**
   * @param string $path
   * @param int $options
   * @return bool
   */
  public function dir_opendir( $path, $options );

  /**
   * @return string
   */
  public function dir_readdir();

  /**
   * @return bool
   */
  public function dir_rewinddir();

  /**
   * @param string $path
   * @param int $mode
   * @param int $options
   * @return bool
   */
  public function mkdir( $path, $mode, $options );

  /**
   * @param string $path_from
   * @param string $path_to
   * @return bool
   */
  public function rename( $path_from, $path_to );

  /**
   * @param string $path
   * @param int $options
   * @return bool
   */
  public function rmdir( $path, $options );

  /**
   * @param int $cast_as
   * @return resource
   */
  public function stream_cast( $cast_as );

  /**
   * @return void
   */
  public function stream_close();

  /**
   * @return bool
   */
  public function stream_eof();

  /**
   * @return bool
   */
  public function stream_flush();

  /**
   * @param int $operation
   * @return bool
   */
  public function stream_lock( $operation );

  /**
   * @param string $path
   * @param int $options
   * @param mixed $value
   * @return bool
   */
  public function stream_metadata( $path, $option, $value );

  /**
   * @param string $path
   * @param string $mode
   * @param int $options
   * @param string $opened_path
   * @return bool
   */
  public function stream_open( $path, $mode, $options, &$opened_path );

  /**
   * @param int $count
   * @return string
   */
  public function stream_read( $count );

  /**
   * @param int $offset
   * @param int $whence
   * @return bool
   */
  public function stream_seek( $offset, $whence = SEEK_SET );

  /**
   * @param int $option
   * @param int $arg1
   * @param int $arg2
   * @return bool
   */
  public function stream_set_option( $option, $arg1, $arg2 );

  /**
   * @return array
   */
  public function stream_stat();

  /**
   * @return int
   */
  public function stream_tell();

  /**
   * @param int $new_size
   * @return bool
   */
  public function stream_truncate( $new_size );

  /**
   * @param string $data
   * @return int
   */
  public function stream_write( $data );

  /**
   * @param string $path
   * @return bool
   */
  public function unlink( $path );

  /**
   * @param string $path
   * @param int $flags
   * @return array
   */
  public function url_stat( $path, $flags );

}
