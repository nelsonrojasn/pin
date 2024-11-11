<?php

/**
 * Template
 * Clase para renderizar vistas dentro de una plantilla
 * @author nelson rojas
 */
class Template 
{
	/**
	 * @var string
	 */
	private $_template = 'default';

	/**
	 * @var string
	 */
	private $_title = '';

	/**
	 * @var array
	 */
	private $_properties = [];

	/**
	 * Método para setear propiedades
	 * @param string $prop
	 * @param mixed $value
	 */
	public function set($prop, $value)
	{
		$this->_properties[$prop] = $value;
	}


	/**
	 * Método para obtener propiedades
	 * @param string $prop
	 * @return mixed|null
	 */
	public function get($prop)
	{
		return isset($this->_properties[$prop]) ? $this->_properties[$prop] : null;
	}

	/**
	 * Permite asignar el template manualmente
	 * @param string $template
	 */
	public function setTemplate($template)
	{
		$this->_template = $template;
	}

	/**
	 * Permite asignar el title manualmente
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->_title = $title;
	}



	/**
	 * Permite renderizar una vista dentro de la plantilla
	 * @param string $view
	 */
	public function render($view)
	{
		$template_file = PIN_PATH . 'templates' . DS . $this->_template . '.phtml';
		
		ob_start();
		load_view($view, $this->_properties);
		$yield = ob_get_clean();
		if (file_exists($template_file)) {
			$title = $this->_title;
			include $template_file;
		} else {
			throw new Exception("Archivo de template no encontrado $template_file", 1);
		}
	}
}