<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Load_vars {
    private 	$directory;
    private 	$files;
    public 		$EnvironmentVariable = array();
    private     $extension = 'cfg';

	public function __construct($directory='cfg', $file='') {
		try {
			$this->directory = APPPATH.$directory;
			$this->files 	 = array($file);
	        self::ensureFileIsReadable();
	        $file OR $this->files = self::get_files();

	        self::load();
		} catch (Exception $e) {
			echo $e->getMesage();
			exit();
		}
	}

	public function load() {
        foreach ($this->files as $file) {
        	list($file, $extension) = explode('.', $file);
        	$filename 	= $file;
        	$$filename 	= array();
        	$filePath 	= $this->directory."/$filename.".$this->extension;
        	if (!is_readable($filePath)) continue;
        	$lines 		= $this->readLinesFromFile($filePath);

        	//GUARDAMOS LA VARIABLE DE ENTORNO
        	foreach ($lines as $line) {
	            if (!$this->isComment($line) && $this->looksLikeSetter($line)) {
	        		$this->setEnvironmentVariable($$filename, $line);
	            }
        	}

        	$this->EnvironmentVariable[$filename] = $$filename;
            unset($$filename);
        }
	}

	protected function get_files() {
        $response = array();
		$files = array_diff(scandir($this->directory), array('.', '..'));
        foreach ($files as $file) {
            if (strrpos($file, '.'.$this->extension)) {
                $response[] = $file;
            }
        }

        count($response) OR exit('No se ha encontrado ningún archivo de configuración, contacte al administrador del sistema.');
		return $response;
	}

    /**
     * Ensures the given filePath is readable.
     * @return void
     */
    protected function ensureFileIsReadable() {
        if (!is_readable($this->directory)) {
            throw new Exception(sprintf('Unable to read the environment file at %s.', $this->directory));
        }
    }

    /**
     * Read lines from the file, auto detecting line endings.
     * @return array
     */
    protected function readLinesFromFile($filePath) {
        // Read file into an array of lines with auto-detected line endings
        $autodetect = ini_get('auto_detect_line_endings');
        ini_set('auto_detect_line_endings', '1');
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        ini_set('auto_detect_line_endings', $autodetect);

        return $lines;
    }

    /**
     * Determine if the line in the file is a comment, e.g. begins with a #.
     * @return bool
     */
    protected function isComment($line) {
        $line = ltrim($line);

        return isset($line[0]) && $line[0] === '#';
    }

    /**
     * Determine if the given line looks like it's setting a variable.
     * @return bool
     */
    protected function looksLikeSetter($line) {
        return strpos($line, '=') !== false;
    }

    /**
     * Set an environment variable.
     *
     * The environment variable value is stripped of single and double quotes.
     * @return void
     */
    public function setEnvironmentVariable(&$file, $line) {
    	list($name, $value) = array_map('trim', explode('=', $line, 2));

    	if (!isset($file[$name])) {
    		$file[$name] = $value;
    		$this->EnvironmentVariable['all'][$name] = $value;
    	}
    }
}

if ( ! function_exists('get_var')) {
	/**
	 * Returns the specified config item
	 *
	 * @param	string
	 * @return	mixed
	 */
	function get_var($item, $value_default='', $file=FALSE) {
		static $env;

		if (empty($env)) {
			// references cannot be directly assigned to static variables, so we use an array
			$env = new Load_vars();
		}

		if ($file) return isset($env->EnvironmentVariable[$file]) ? $env->EnvironmentVariable[$file] : NULL;

		return isset($env->EnvironmentVariable['all'][$item]) ? $env->EnvironmentVariable['all'][$item] : $value_default;
	}
}

/* End of file Load_vars.php */
/* Location: ./application/libraries/Load_vars.php */
