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

use Substance\Core\Alert\Alert;

/**
 * Convenience class for implementing local file based stream wrappers.
 *
 * These local files based stream wrappers are based on a root folder which
 * they cannot see outside of (similar to a chroot).
 */
abstract class AbstractLocalSubstanceStreamWrapper implements SubstanceStreamWrapper {

  /**
   * @var resource
   */
  public $context;

  /**
   * @var resource
   */
  protected $handle;

  /**
   * Base URI for the root of this stream wrapper.
   *
   * @var string
   */
  protected $base_uri;

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::dir_closedir()
   */
  public function dir_closedir() {
    closedir( $this->handle, $this->context );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::dir_opendir()
   */
  public function dir_opendir( $path, $options ) {
    $path = $this->resolveRealpath( $path );

    $this->handle = opendir( $path, $this->context );

    return $this->handle !== FALSE;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::dir_readdir()
   */
  public function dir_readdir() {
    return readdir( $this->handle );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::dir_rewinddir()
   */
  public function dir_rewinddir() {
    rewinddir( $this->handle );
    // Assume rewinddir works, as we have no way of telling.
    return TRUE;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::mkdir()
   */
  public function mkdir( $path, $mode, $options ) {
    $path = $this->resolveRealpath( $path );

    // Official documentation at http://uk3.php.net/manual/en/streamwrapper.mkdir.php
    // does not specify what all the options are, just STREAM_MKDIR_RECURSIVE
    mkdir( $path, $mode, $options & STREAM_MKDIR_RECURSIVE, $this->context );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::rename()
   */
  public function rename( $path_from, $path_to ) {
    $path_from = $this->resolveRealpath( $path_from );
    $path_to = $this->resolveRealpath( $path_to );

    return rename( $path_from, $path_to, $this->context );
  }

  /**
   * Resolves the specified uri relative to the stream base URI.
   *
   * @param string $uri
   */
  public function resolve( $uri ) {
    $segments = explode( '/', $uri );
    $canonical_segments = array();
    while ( count( $segments ) > 0 ) {
      $segment = array_shift( $segments );
      switch ( $segment ) {
        case '.':
          // Do nothing, it's the "current" segment.
          break;
        case '..':
          // Go up a segment.
          if ( count( $canonical_segments ) == 0 ) {
            // We cannot go "up" beyond the root of this stream.
            throw Alert::alert('Attempting to go outside the root');
          } else {
            // Remove the last segment
            array_pop( $canonical_segments );
          }
          break;
        default:
          // It's a normal path segment, so add it to the canonical version.
          $canonical_segments[] = $segment;
          break;
      }
    }
    array_unshift( $canonical_segments, $this->base_uri );
    return implode( '/', $canonical_segments );
  }

  /**
   * Resolves the specified uri relative to the stream base URI and
   * canonicalises it to an absolute pathname.
   *
   * @param string $uri
   */
  public function resolveRealpath( $uri ) {
    $path = $this->resolve( $uri );
    $realpath = realpath( $path );
    if ( $realpath === FALSE ) {
      throw Alert::alert('URI does not exist')
        ->culprit( 'URI', $uri );
    }
    return $realpath;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::rmdir()
   */
  public function rmdir( $path, $options ) {
    $path = $this->resolveRealpath( $path );

    // Official documentation at http://uk3.php.net/manual/en/streamwrapper.mkdir.php
    // does not specify what all the options are, just STREAM_MKDIR_RECURSIVE.
    // FIXME - rmdir() does not allow recursive delete, so we must implement it
    // ourselves.
    rmdir( $path, $this->context );
  }

  protected function setBaseURI( $uri ) {
    $realdir = realpath( $uri );
    if ( $realdir === FALSE ) {
      throw Alert::alert('URI does not exist')->culprit( 'URI', $uri );
    }
    if ( is_dir( $realdir ) ) {
      throw Alert::alert('URI is not a directory')->culprit( 'URI', $uri );
    }
    $this->base_uri = $realdir;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::stream_cast()
   */
  public function stream_cast( $cast_as ) {
    // FIXME
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::stream_close()
   */
  public function stream_close() {
    return fclose( $this->handle );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::stream_eof()
   */
  public function stream_eof() {
    return feof( $this->handle );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::stream_flush()
   */
  public function stream_flush() {
    return fflush( $this->handle );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::stream_lock()
   */
  public function stream_lock( $operation ) {
    return flock( $this->handle, $operation );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::stream_metadata()
   */
  public function stream_metadata( $path, $option, $value ) {
    // FIXME
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::stream_open()
   */
  public function stream_open( $path, $mode, $options, &$opened_path ) {
    $path = $this->resolveRealpath( $path );

    if ( $options & STREAM_REPORT_ERRORS ) {
      $this->handle = fopen( $path, $mode, false, $this->context );
    } else {
      $this->handle = @fopen( $path, $mode, false, $this->context );
    }

    if ( $options & STREAM_USE_PATH ) {
      $opened_path = $path;
    }
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::stream_read()
   */
  public function stream_read( $count ) {
    return fread( $this->handle, $count );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::stream_seek()
   */
  public function stream_seek( $offset, $whence ) {
    return fseek( $this->handle, $offset, $whence );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::stream_set_option()
   */
  public function stream_set_option( $option, $arg1, $arg2 ) {
    // FIXME
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::stream_stat()
   */
  public function stream_stat() {
    return fstat( $this->handle );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::stream_tell()
   */
  public function stream_tell() {
    return ftell( $this->handle );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::stream_truncate()
   */
  public function stream_truncate( $new_size ) {
    return ftruncate( $this->handle, $new_size );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::stream_write()
   */
  public function stream_write( $data ) {
    return fwrite( $this->handle, $data );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::unlink()
   */
  public function unlink( $path ) {
    $path = $this->resolveRealpath( $path );

    return unlink( $path, $this->context );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\StreamWrappers\StreamWrapper::url_stat()
   */
  public function url_stat( $path, $flags ) {
    $path = $this->resolveRealpath( $path );

    if ( $flags & STREAM_URL_STAT_LINK && is_link( $path ) ) {
      if ( $flags & STREAM_URL_STAT_QUIET ) {
        return @lstat( $path );
      } else {
        return lstat( $path );
      }
    } else {
      if ( $flags & STREAM_URL_STAT_QUIET ) {
        return @stat( $path );
      } else {
        return stat( $path );
      }
    }
  }

}
