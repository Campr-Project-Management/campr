<?php
/*
    @author dhtmlx.com
    @license GPL, see license.txt
*/
require_once 'base_connector.php';
require_once 'grid_config.php';

//require_once("grid_dataprocessor.php");

/*! DataItem class for Grid component
**/

class GridDataItem extends DataItem
{
    protected $row_attrs; //!< hash of row attributes
    protected $cell_attrs; //!< hash of cell attributes

    public function __construct($data, $name, $index = 0)
    {
        parent::__construct($data, $name, $index);

        $this->row_attrs = array();
        $this->cell_attrs = array();
    }
    /*! set color of row

        @param color
            color of row
    */
    public function set_row_color($color)
    {
        $this->row_attrs['bgColor'] = $color;
    }
    /*! set style of row

        @param color
            color of row
    */
    public function set_row_style($color)
    {
        $this->row_attrs['style'] = $color;
    }
    /*! assign custom style to the cell

        @param name
            name of column
        @param value
            css style string
    */
    public function set_cell_style($name, $value)
    {
        $this->set_cell_attribute($name, 'style', $value);
    }
    /*! assign custom class to specific cell

        @param name
            name of column
        @param value
            css class name
    */
    public function set_cell_class($name, $value)
    {
        $this->set_cell_attribute($name, 'class', $value);
    }
    /*! set custom cell attribute

        @param name
            name of column
        @param attr
            name of attribute
        @param value
            value of attribute
    */
    public function set_cell_attribute($name, $attr, $value)
    {
        if (!array_key_exists($name, $this->cell_attrs)) {
            $this->cell_attrs[$name] = array();
        }
        $this->cell_attrs[$name][$attr] = $value;
    }

    /*! set custom row attribute

        @param attr
            name of attribute
        @param value
            value of attribute
    */
    public function set_row_attribute($attr, $value)
    {
        $this->row_attrs[$attr] = $value;
    }

    /*! return self as XML string, starting part
    */
    public function to_xml_start()
    {
        if ($this->skip) {
            return '';
        }

        $str = "<row id='".$this->xmlentities($this->get_id())."'";
        foreach ($this->row_attrs as $k => $v) {
            $str .= ' '.$k."='".$v."'";
        }
        $str .= '>';
        for ($i = 0; $i < sizeof($this->config->text); ++$i) {
            $str .= '<cell';
            $name = $this->config->text[$i]['name'];
            $xmlcontent = false;
            if (isset($this->cell_attrs[$name])) {
                $cattrs = $this->cell_attrs[$name];
                foreach ($cattrs as $k => $v) {
                    $str .= ' '.$k."='".$this->xmlentities($v)."'";
                    if ($k == 'xmlcontent') {
                        $xmlcontent = true;
                    }
                }
            }
            $value = isset($this->data[$name]) ? $this->data[$name] : '';
            if (!$xmlcontent) {
                $str .= '><![CDATA['.$value.']]></cell>';
            } else {
                $str .= '>'.$value.'</cell>';
            }
        }
        if ($this->userdata !== false) {
            foreach ($this->userdata as $key => $value) {
                $str .= "<userdata name='".$key."'><![CDATA[".$value.']]></userdata>';
            }
        }

        return $str;
    }
    /*! return self as XML string, ending part
    */
    public function to_xml_end()
    {
        if ($this->skip) {
            return '';
        }

        return '</row>';
    }
}
/*! Connector for the dhtmlxgrid
**/
class GridConnector extends Connector
{
    /*! constructor

        Here initilization of all Masters occurs, execution timer initialized
        @param res
            db connection resource
        @param type
            string , which hold type of database ( MySQL or Postgre ), optional, instead of short DB name, full name of DataWrapper-based class can be provided
        @param item_type
            name of class, which will be used for item rendering, optional, DataItem will be used by default
        @param data_type
            name of class which will be used for dataprocessor calls handling, optional, DataProcessor class will be used by default.
    */
    public function __construct($res, $type = false, $item_type = false, $data_type = false, $render_type = false)
    {
        if (!$item_type) {
            $item_type = 'GridDataItem';
        }
        if (!$data_type) {
            $data_type = 'GridDataProcessor';
        }
        if (!$render_type) {
            $render_type = 'RenderStrategy';
        }
        parent::__construct($res, $type, $item_type, $data_type, $render_type);
    }

    protected function parse_request()
    {
        parent::parse_request();

        if (isset($_GET['dhx_colls'])) {
            $this->fill_collections($_GET['dhx_colls']);
        }
    }
    protected function resolve_parameter($name)
    {
        if (intval($name).'' == $name) {
            return $this->config->text[intval($name)]['db_name'];
        }

        return $name;
    }

    /*! replace xml unsafe characters

        @param string
            string to be escaped
        @return
            escaped string
    */
    protected function xmlentities($string)
    {
        return str_replace(array('&', '"', "'", '<', '>', '’'), array('&amp;', '&quot;', '&apos;', '&lt;', '&gt;', '&apos;'), $string);
    }

    /*! assign options collection to the column

        @param name
            name of the column
        @param options
            array or connector object
    */
    public function set_options($name, $options)
    {
        if (is_array($options)) {
            $str = '';
            foreach ($options as $k => $v) {
                $str .= "<item value='".$this->xmlentities($k)."' label='".$this->xmlentities($v)."' />";
            }
            $options = $str;
        }
        $this->options[$name] = $options;
    }
    /*! generates xml description for options collections

        @param list
            comma separated list of column names, for which options need to be generated
    */
    protected function fill_collections($list = '')
    {
        $names = explode(',', $list);
        for ($i = 0; $i < sizeof($names); ++$i) {
            $name = $this->resolve_parameter($names[$i]);
            if (!array_key_exists($name, $this->options)) {
                $this->options[$name] = new DistinctOptionsConnector($this->get_connection(), $this->names['db_class']);
                $c = new DataConfig($this->config);
                $r = new DataRequestConfig($this->request);
                $c->minimize($name);

                $this->options[$name]->render_connector($c, $r);
            }

            $this->extra_output .= "<coll_options for='{$names[$i]}'>";
            if (!is_string($this->options[$name])) {
                $this->extra_output .= $this->options[$name]->render();
            } else {
                $this->extra_output .= $this->options[$name];
            }
            $this->extra_output .= '</coll_options>';
        }
    }

    /*! renders self as  xml, starting part
    */
    protected function xml_start()
    {
        $attributes = '';
        foreach ($this->attributes as $k => $v) {
            $attributes .= ' '.$k."='".$v."'";
        }

        if ($this->dload) {
            if ($pos = $this->request->get_start()) {
                return "<rows pos='".$pos."'".$attributes.'>';
            } else {
                return "<rows total_count='".$this->sql->get_size($this->request)."'".$attributes.'>';
            }
        } else {
            return '<rows'.$attributes.'>';
        }
    }

    /*! renders self as  xml, ending part
    */
    protected function xml_end()
    {
        return $this->extra_output.'</rows>';
    }

    public function set_config($config = false)
    {
        if (gettype($config) == 'boolean') {
            $config = new GridConfiguration($config);
        }

        $this->event->attach('beforeOutput', array($config, 'attachHeaderToXML'));
        $this->event->attach('onInit', array($config, 'defineOptions'));
    }
}

/*! DataProcessor class for Grid component
**/
class GridDataProcessor extends DataProcessor
{
    /*! convert incoming data name to valid db name
        converts c0..cN to valid field names
        @param data
            data name from incoming request
        @return
            related db_name
    */
    public function name_data($data)
    {
        if ($data == 'gr_id') {
            return $this->config->id['name'];
        }
        $parts = explode('c', $data);
        if ($parts[0] == '' && ((string) intval($parts[1])) == $parts[1]) {
            if (sizeof($this->config->text) > intval($parts[1])) {
                return $this->config->text[intval($parts[1])]['name'];
            }
        }

        return $data;
    }
}
