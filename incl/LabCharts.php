<?php
/*
 * LabCharts version 1.2
 * http://www.code-laboratory.com/
 *
 * Copyright (c) 2009 http://code-laboratory.com
 * Dual licensed under the MIT and GPL licenses.
 *
 * 2009-12-04 19:00
 * 2010-01-30 13:25 edited to v 1.1
 * 2010-05-22 22:47 edited to v 1.2
 */

abstract class LabCharts {
    
    protected $basePath = 'http://chart.apis.google.com/chart?';
    protected $baseData;
    // charts properties
    protected $_type;
    protected $_data;
    protected $_size;
    protected $_colors;
    protected $_labelsPie;
    protected $_labelsBar;
    protected $_title;
    protected $_stylesBar;
    protected $_axis;
    protected $_axisRange;
    protected $_axisPosition;
    protected $_grids;
    protected $_background;
    
    abstract protected function dataToString($data);
    
    public function setData (array $data) {
		$this->baseData = $data;
		$this->_data = $this->dataToString($data);
	}
    public function setSize ($size) {
		$this->_size = $size;
	}
    public function setTitle ($title) {
		$this->_title = $this->changeTitle($title);
	}
    public function setColors ($colors) {
		$this->_colors = $colors;
	}
	public function setBackground ($color) {
		$this->_background = $color;
	}
    
    public function getChart () {
        $href = $this->basePath;
        $href .= 'cht=' . $this->_type;
        $href .= '&chs=' . $this->_size;
        $href .= '&chd=' . $this->_data;
        if ($this->_title) $href .= '&chtt=' . $this->_title;
        if ($this->_colors) $href .= '&chco=' . $this->_colors;
        if ($this->_labelsPie) $href .= '&chl=' . $this->_labelsPie;
        if ($this->_labelsBar) $href .= '&chdl=' . $this->_labelsBar;
        if ($this->_stylesBar) $href .= '&chbh=' . $this->_stylesBar;
        if ($this->_axis) $href .= '&chxt=' . $this->_axis;
        if ($this->_axisRange) $href .= '&chxl=' . $this->_axisRange;
        if ($this->_axisPosition) $href .= '&chxp=' . $this->_axisPosition;
        if ($this->_grids) $href .= '&chg=' . $this->_grids;
        if ($this->_background) $href .= '&chf=bg,s,' . $this->_background;
        $href .= '&PoweredBy_Code-Laboratory.Com';
        return $href;
    }

    private function changeTitle ($title) {
        return str_replace(' ', '+', $title);
    }
    
}

?>
