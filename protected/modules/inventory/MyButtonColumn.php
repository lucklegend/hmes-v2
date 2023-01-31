<?php 

Yii::import('bootstrap.widgets.TbButtonColumn');

class MyButtonColumn extends TbButtonColumn
{
    protected function renderButton($id, $button, $row, $data)
    {
        if (isset($button['visible']) && !$this->evaluateExpression(
            $button['visible'],
            array('row' => $row, 'data' => $data)
        )
        ) {
            return;
        }

        $label = isset($button['label']) ? $button['label'] : $id;
        $url = isset($button['url']) ? $this->evaluateExpression($button['url'], array('data' => $data, 'row' => $row))
            : '#';
        $options = isset($button['options']) ? $button['options'] : array();
        
        //my add starts here
        if(isset($options['id'])){
            $id = $this->evaluateExpression($options['id'],array('data' => $data, 'row' => $row));
            if($options['function']='updateTag'){
            $options['onclick']="{updateTag(".$id."); $('#mydialog').dialog('open');}";
            }
        }
        //my add ends here

        if (!isset($options['title'])) {
            $options['title'] = $label;
        }

        if (!isset($options['rel'])) {
            $options['rel'] = 'tooltip';
        }

        if (isset($button['icon'])) {
            if (strpos($button['icon'], 'icon') === false) {
                $button['icon'] = 'icon-' . implode(' icon-', explode(' ', $button['icon']));
            }

            echo CHtml::link('<i class="' . $button['icon'] . '"></i>', $url, $options);
        } else if (isset($button['imageUrl']) && is_string($button['imageUrl'])) {
            echo CHtml::link(CHtml::image($button['imageUrl'], $label), $url, $options);
        } else {
            echo CHtml::link($label, $url, $options);
        }
    }
}