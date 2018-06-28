<?php
Yii::import('zii.widgets.grid.CGridView');
class GridView extends CGridView
{

    private $idProcessDetailSplit = 0;
    private $maxIdProcessDetailSplit = 0;

    public function renderTableRow($row)
    {
        parent::renderTableRow($row);
        $data=$this->dataProvider->data[$row];
        //print_r($data);
        //echo $data['booking_id'];
        /*$data=$this->dataProvider->data[$row];
        $qRoomType = $data->getRoomType();
        $cntDetail = count($qRoomType);
        if($cntDetail > 0) {*/
            $htmlOptions = $this->getRowHtmlOptions($row);
            $columnCount = count($this->columns);
            echo CHtml::openTag('tr', $htmlOptions)."\n";
            echo "<td colspan=\"{$columnCount}\">";
            echo '<div class="cls-table-detail">';
            echo "<table cellpadding=\"3\" cellspacing=\"0\" style=\"width: auto; margin-bottom: 0em;\">";
            echo "<tr>";
            /*echo "<th>Room Type</th>";
            echo "<th>Cleaning Minutes</th>";
            echo "<th>Rack Rate</th>";
            echo "<th>Max. Occupants</th>";
            echo "<th>Rooms</th>";*/
            echo "<th class='button-column' style='width: 20px;'>View RSVN</th>";
            echo "<th class='button-column' style='width: 20px;'>Accept Booking</th>";
            echo "<th class='button-column' style='width: 20px;'>Amend Hotel</th>";
            echo "<th class='button-column' style='width: 20px;'>Cancel RSVN</th>";
            echo "</tr>";
            //foreach ($qRoomType as $vRoomType) {
                echo "<tr>";
                //echo "<td>".$vRoomType['room_type_name']."</td>";
                //echo "<td class=\"col-right\">".$this->getFormatter()->format($vRoomType['room_type_cleaning_minutes'], 'number0')."</td>";
                //echo "<td class=\"col-right\">".$this->getFormatter()->format($vRoomType['room_type_rack_rate'], 'number0')."</td>";
                //echo "<td class=\"col-right\">".$this->getFormatter()->format($vRoomType['room_type_maximum_occupants'], 'number0')."</td>";
                //echo "<td class=\"col-right\">".CHtml::link(CHtml::image(Yii::app()->theme->baseUrl.'/images/create.png', 'Create'), array('/master/room/update', 'id'=>$vRoomType['room_type_id']), array('title'=>'Room')).'&nbsp;'."</td>";
                echo '<td class="button-column">';
                //echo $data['booking_id'];
                echo CHtml::link(CHtml::image(Yii::app()->theme->baseUrl.'/images/reservation.png', 'Reservation'), array('/mg/hoteldata/Getrsvninfo', 'id'=>$data['booking_id']), array('title'=>'Reservation')).'&nbsp;';

                //echo CHtml::link(CHtml::image(Yii::app()->theme->baseUrl.'/images/bed.png', 'Bed'), array('/master/roomtypebed/update', 'id'=>$vRoomType['room_type_id']), array('title'=>'Bed')).'&nbsp;';
                //echo CHtml::link(CHtml::image(Yii::app()->theme->baseUrl.'/images/features.png', 'Features'), array('/master/roomtypefeatures/create', 'id'=>$vRoomType['room_type_id']), array('title'=>'Features')).'&nbsp;';
                //echo CHtml::link(CHtml::image(Yii::app()->theme->baseUrl.'/images/Camera.png', 'Photo'), array('/master/roomphoto/create', 'id'=>$vRoomType['room_type_id']), array('title'=>'Photo')).'&nbsp;';
                //echo CHtml::link(CHtml::image(Yii::app()->theme->baseUrl.'/images/price.png', 'Price'), array('/partner/Basepriceroom/update', 'id'=>$vRoomType['room_type_id']), array('title'=>'Price')).'&nbsp;';
                //echo CHtml::link(CHtml::image(Yii::app()->theme->baseUrl.'/images/bulk.png', 'Bulk'), array('/master/Roompriceflexible/updatebulk', 'id'=>$vRoomType['room_type_id']), array('title'=>'Bulk')).'&nbsp;';
                //echo CHtml::link(CHtml::image($this->baseScriptUrl.'/update.png', 'view'), array('/master/roomtype/update', 'id'=>$vRoomType['room_type_id']), array('title'=>'Update')).'&nbsp;';
                //echo CHtml::link(CHtml::image($this->baseScriptUrl.'/delete.png', 'view'), array('/master/roomtype/delete', 'id'=>$vRoomType['room_type_id']), array('class'=>'delete', 'title'=>'Delete'));
                echo "</td>";
                echo '<td class="button-column">';
                echo CHtml::link(CHtml::image(Yii::app()->theme->baseUrl.'/images/bed.png', 'Bed'), array('/mg/hoteldata/AcceptBooking', 'id'=>$data['booking_id']), array('title'=>'Bed')).'&nbsp;';
                echo "</td>";
                echo '<td class="button-column">';
                echo CHtml::link(CHtml::image(Yii::app()->theme->baseUrl.'/images/update.png', 'Update'), array('/mg/hoteldata/AmendHotel', 'id'=>$data['booking_id']), array('title'=>'Amend Hotel')).'&nbsp;';
                echo "</td>";
                echo '<td class="button-column">';
                echo CHtml::link(CHtml::image(Yii::app()->theme->baseUrl.'/images/delete.png', 'Delete'), array('/mg/hoteldata/Cancelrsvn', 'id'=>$data['booking_id']), array('title'=>'Cancel RSVN')).'&nbsp;';
                echo "</td>";
                echo "</tr>";
            //}
            echo "</table>";
            echo '</div>';
            echo "</td>";
            echo "</tr>\n";
        //}
    }

    private function getRowHtmlOptions($row)
    {
        $htmlOptions=array();
        if($this->rowHtmlOptionsExpression!==null)
        {
            $data=$this->dataProvider->data[$row];
            $options=$this->evaluateExpression($this->rowHtmlOptionsExpression,array('row'=>$row,'data'=>$data));
            if(is_array($options))
                $htmlOptions = $options;
        }

        if($this->rowCssClassExpression!==null)
        {
            $data=$this->dataProvider->data[$row];
            $class=$this->evaluateExpression($this->rowCssClassExpression,array('row'=>$row,'data'=>$data));
        }
        elseif(is_array($this->rowCssClass) && ($n=count($this->rowCssClass))>0)
            $class=$this->rowCssClass[$row%$n];

        if(!empty($class))
        {
            if(isset($htmlOptions['class']))
                $htmlOptions['class'].=' '.$class;
            else
                $htmlOptions['class']=$class;
        }
        return $htmlOptions;
    }
}
