<?php

class AdsTxtManager {

	private $path;
	private $url;

	public function __construct( $pathToAdsTxtFile, $urlToAdsTxtFile = null ) {
		$this->path = $pathToAdsTxtFile;
		$this->url  = $urlToAdsTxtFile;
	}

	public function isFileReadable() {
		return is_readable( $this->path );
	}

	public function getFileContent() {
		if ( ! is_readable( $this->path ) ) {
			return false;
		}

		return file_get_contents( $this->path );
	}

	public function getLines() {
		if ( ! is_readable( $this->path ) ) {
			return false;
		}

		$lines                = file( $this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
		$linesWithoutComments = array();

		foreach ( $lines as $line ) {
			$trimmedLine = trim( $line );
			if ( $trimmedLine[0] === '#' ) {
				continue;
			}

			$linesWithoutComments[] = $trimmedLine;
		}

		return $linesWithoutComments;
	}

	public function update( $text ) {
		return file_put_contents( $this->path, $text );
	}

	public function saveData( $data ) {
		$lines = array(
			$this->getTopComment(),
			''
		);
		foreach ( $data as $rowArray ) {
			// Do this in case use passes associative array
			$rowArray = array_values( $rowArray );
			// Don't save rows with missing required data
			if ( empty( $rowArray[0] ) || empty( $rowArray[1] ) || empty( $rowArray[2] ) ) {
				continue;
			}

			$rowArray = array_map( 'trim', $rowArray );
			$line     = join( ', ', $rowArray );
			$lines[]  = trim( $line, ', ' );
		}

		return $this->saveLines( $lines );
	}

	protected function getAdsTxtUrl() {
		return ( $this->url ? $this->url : 'ads.txt' );
	}

	protected function getTopComment() {
		return '# Ads.txt ' . $this->getAdsTxtUrl();
	}

	public function saveNoAuthorisedSellers() {
		$lines = array(
			$this->getTopComment(),
			sprintf( '# There are no authorised sellers or resellers listed in this %s file.', $this->getAdsTxtUrl() ),
			''
		);

		return $this->saveLines( $lines );
	}

	protected function saveLines( $lines ) {
		return file_put_contents( $this->path, join( "\r\n", $lines ) );
	}

}