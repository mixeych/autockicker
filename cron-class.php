<?php 
class TwitterShedules
{
    public $times;
    protected $post_id;

    public function __construct($id)
    {
        $t = get_post_meta($id, 'autokick_times', true);
        if(empty($t)){
            $this->times = 0;
        }else{
            $this->times = $t;
        }
        $this->post_id = $id;
    }

    public function add_shedule($time)
    {
        if( ! wp_next_scheduled( 'autokick', array($this->post_id) ) ) {
            wp_schedule_single_event($time, 'autokick', array($this->post_id));
        }
    }


}
?>