<?php namespace App\Libraries;

class Pager{
    function get_pagination($details){
        $total = $details['total'];
        $per_page = $details['per_page'];
        $offset = $details['offset'];
        $divide_total = (int) ceil($total / $per_page);
        $pageList = $this->get_pages($divide_total);
        $add_active = $this->add_active($pageList, $offset);
        $pages = $add_active['pages'];
        $active_idx = $add_active['active_idx'];

        //no of pages to show per page 
        $page_number = 6;

        $page_minus = round ($page_number / 3) * 2;

        $sliceStart = $offset >= $page_number ?  $offset - $page_minus   : 0;
        $pages_sliced = array_slice($pages, $sliceStart, $page_number);

        $pagination_list = $this->organize_pages($pages_sliced, $offset, $divide_total, $active_idx);

        return $pagination_list;
    }
    public function get_pages($divide_total){
        $pages = [];
        for($i = 1; $i <= ($divide_total); $i++){
            $pages[] = [
                'index' => $i,
                'title' => $i,
                'value' => $i,
            ]; 
        }
        return $pages;
    }
    public function add_active($pages, $offset){
        $active_idx = null;
        foreach ($pages as $i => $page) {
            $check  = $i == $offset - 1;
            $pages[$i]['active'] = $check;
            if($check) $active_idx = $i;
        }
        return [
            "active_idx" => $active_idx,
            "pages" => $pages,
        ];
    }
    public function organize_pages($pages, $offset, $divide_total, $active_idx){
        if($offset > 1){
            unset($pages[0]);
            if($offset > 3){
                array_unshift($pages, [
                    'page' => $divide_total,
                    'title' => '&laquo',
                    'value' => $active_idx,
                    'active' => false
                ]);
            }
            array_unshift($pages, [
                'page' => 1,
                'title' => 'First',
                'value' => 1,
                'active' => false
            ]);
        }
        if($offset < $divide_total ){
            $pages[] = [
                'page' => $divide_total,
                'title' => '&raquo',
                'value' => $active_idx + 2,
                'active' => false
            ];
        }
       if($offset != $divide_total){
            // unset($pages[$divide_total]);
            $pages[] = [
                'page' => $divide_total,
                'title' => 'Last',
                'value' => $divide_total,
                'active' => false
            ];
        }
        return $pages;
    }
}
?>