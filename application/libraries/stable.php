<?php
class Stable
{
    var $rows=array();
    var $cols=array();
    var $foot=array();
    var $newline= "\n";
    var $template=array();
    var $caption=null;

    function generate()
    {
            $out='';
            if ($this->caption)
	    {
		$out .= $this->newline;
		$out .= '<caption>' . $this->caption . '</caption>';
		$out .= $this->newline;
	    }
            $out .= $this->template['table_open'];
            $out .= $this->newline;
            $out .= $this->template['heading_start'];
            $out .= $this->newline;
            $out .= $this->template['heading_row_start'];
            $out .= $this->newline;
            foreach($this->cols as $head)
            {
               foreach($head as $title)
               {
                   $out.=$this->template['heading_cell_start'].$title.$this->template['heading_cell_end'];
                   $out.=$this->newline;
               }
            }
            $out .= $this->template['heading_row_end'];
            $out .= $this->newline;
            $out .= $this->template['heading_end'];
            $out .= $this->newline;

            $out .= $this->template['body_start'];
            $out .= $this->newline;

            $i=0;
            foreach ($this->rows as $row)
            {
                if($i==0){
                    $out.='<tr class="first">';
                    $out.= $this->newline;
                }
                else {
                    $out.= $this->template['row_start'];
                    $out.= $this->newline;
                }

                $alt= ($i++%2==0) ? '':'alt_';
                foreach($row as $cell)
                {
                    $out.=$this->template['cell_'.$alt.'start'].$cell.$this->template['cell_'.$alt.'end'];
                }

                $out.= $this->template['row_end'];
                $out.= $this->newline;
            }
            $out .= $this->template['body_end'];
            $out .= $this->newline;

            $out .= $this->template['foot_start'];
            $out .= $this->newline;
            $out .= $this->template['foot_row_start'];
            $out .= $this->newline;
            foreach($this->foot as $sum)
            {
               foreach($sum as $end)
               {
                   $out.=$this->template['foot_cell_start'].$end.$this->template['foot_cell_end'];
                   $out.=$this->newline;
               }
            }
            $out .= $this->template['foot_row_end'];
            $out .= $this->newline;
            $out .= $this->template['foot_end'];
            $out .= $this->newline;
            $out .= $this->template['table_close'];
            return  $out;
    }
    function set_heading()
    {
        $args = func_get_args();
	$this->cols[] = (is_array($args[0])) ? $args[0] : $args;
    }
    function set_caption($caption)
    {
    	$this->caption = $caption;
    }
    function set_foot()
    {
        $args = func_get_args();
	$this->foot[] = (is_array($args[0])) ? $args[0] : $args;
    }
    function add_row()
    {
	$args = func_get_args();
	$this->rows[] = (is_array($args[0])) ? $args[0] : $args;
    }
    function set_template($template)
    {
        if ( ! is_array($template))
        {
            return FALSE;
	}
	$this->template = $template;
    }
}
?>
