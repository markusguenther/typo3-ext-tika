<?php
namespace ApacheSolrForTypo3\Tika;

/***************************************************************
*  Copyright notice
*
*  (c) 2015 Ingo Renner <ingo@typo3.org>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/



/**
 * Run, check, and stop external processes.
 * Linux only. (Windows does not work).
 *
 * @author dell_petter@hotmail.com
 * @see http://php.net/manual/en/function.exec.php#88704
 * @package ApacheSolrForTypo3\Tika
 */
class Process {

	/**
	 * Process ID
	 *
	 * @var integer
	 */
	protected $pid = NULL;

	/**
	 * Command to execute
	 *
	 * @var string
	 */
	protected $command;


	/**
	 * Constructor
	 *
	 * @param string $command
	 */
	public function __construct($command = '') {
		if (!empty($command)) {
			$this->command = $command;
			$this->runCommand();
		}
	}

	/**
	 * Executes the command
	 *
	 * @return void
	 */
	protected function runCommand() {
		$command = 'nohup ' . $this->command . ' > /dev/null 2>&1 & echo $!';
		exec($command, $output);
		$this->pid = (int) $output[0];
	}

	/**
	 * Sets the process ID
	 *
	 * @param integer $pid
	 * @return void
	 */
	public function setPid($pid) {
		$this->pid = (int) $pid;
	}

	/**
	 * Gets the process ID
	 *
	 * @return int process ID
	 */
	public function getPid() {
		return $this->pid;
	}

	/**
	 * Checks whether the process is running
	 *
	 * @return bool TRUE if the process is running, FALSE otherwise
	 */
	public function isRunning() {
		$running = FALSE;

		$command = 'ps -p ' . $this->pid;
		exec($command, $output);

		if (isset($output[1])) {
			$running = TRUE;
		}

		return $running;
	}

	/**
	 * Starts the process.
	 *
	 * @return bool TRUE if the process could be started, FALSE otherwise
	 */
	public function start() {
		$status = FALSE;

		if ($this->command != '') {
			$this->runCommand();
			$status = $this->isRunning();
		}

		return $status;
	}

	/**
	 * Stops the process
	 *
	 * @return bool
	 */
	public function stop() {
		$stopped = NULL;

		$command = 'kill ' . $this->pid;
		exec($command);

		if ($this->isRunning() == FALSE) {
			$stopped = TRUE;
		} else {
			$stopped = FALSE;
		}

		return $stopped;
	}
}
