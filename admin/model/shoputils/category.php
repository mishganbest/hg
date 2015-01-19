<?php
class ModelShoputilsCategory extends Model {
    private $categories = null;

    public function getRootCategories($parent_id = 0){
        $category_data = $this->cache->get('category.' . $this->config->get('config_language_id') . '._' . $parent_id);

        $this->load->model('catalog/category');

        if (!$category_data) {
            $category_data = array();

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");

            foreach ($query->rows as $result) {
                $category_data[] = array(
                    'category_id' => $result['category_id'],
                    'name'        => $this->model_catalog_category->getPath($result['category_id'], $this->config->get('config_language_id')),
                    'status'  	  => $result['status'],
                    'sort_order'  => $result['sort_order']
                );
            }

            $this->cache->set('category.' . $this->config->get('config_language_id') . '._' . $parent_id, $category_data);
        }

        return $category_data;
    }

    public function getCategories() {
        if ($this->categories){
            return $this->categories;
        }

        if (isset($this->session->data['selected_store_id'])){
            $store_id = $this->session->data['selected_store_id'];
            $selected_store_condition = ' AND c2s.store_id = '.$this->session->data['selected_store_id'];
        } else {
            $store_id = '';
            $selected_store_condition = ' ';
        }

       // $this->categories = $this->cache->get('category.' . $this->config->get('config_language_id') . '.shoputils.'.$store_id);

        if (!$this->categories) {
            $query = $this->db->query("SELECT c.category_id, c.parent_id, cd.name, c.sort_order, name
                FROM " . DB_PREFIX . "category c
                LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)
                LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id)
                WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ". $selected_store_condition . "
                ORDER BY c.sort_order, cd.name ASC");
            $this->categories = $this->makeTree($query->rows, 'category_id', 'parent_id');

            if (isset($this->categories[0])){
                $this->categories = $this->getSubCategories($this->categories[0]);
            } else {
                $this->categories = array();
            }

            $this->cache->set('category.' . $this->config->get('config_language_id') . '.shoputils.' . $store_id, $this->categories);
        }
        return $this->categories;
    }

    private function getSubCategories($nodes = array(), $level = 0, $line = '', $name = '') {
        $result = array();
        $new_line = $line;
        $count = 0;
        foreach ($nodes as $node){
            $count++;
            if ($level > 0) {
                if ($level == 1){
                    if ($count == count($nodes) && !isset($node['children'])){
                        $new_line = '└';
                    } else {
                        $new_line = '├';
                    }
                } else {
                    if ($count == count($nodes)){
                        $new_line = '┴';
                    } else {
                        $new_line = '┼';
                    }
                }
            }

            $result[] = array(
                'category_id' => $node['category_id'],
                'parent_id' => $node['parent_id'],
                'name' => $line.$new_line.$node['name'],
                'fullname' => ($name ? $name . ' > ' : '') . $node['name'],
            );
            
            if (isset($node['children'])){
                $result = array_merge($result, $this->getSubCategories($node['children'], $level + 1, $line.$new_line, ($name ? $name . ' > ' : '').$node['name']));
            }
        }
        return $result;
    }

    function makeTree($arr, $id_key = 'category_id', $pid_key = 'parent_id') {
        $structure = array();
        $references = array();
        while($elem = array_shift($arr)) {
            if(isset($structure[ $elem[$id_key] ])) {
                $elem['children'] = $structure[ $elem[$id_key] ];
                unset($structure[ $elem[$id_key] ]);
            } else
                $elem['children'] = array();
            if(isset($references[ $elem[$pid_key] ])) {
                $references[ $elem[$pid_key] ]['children'][ $elem[$id_key] ] = $elem;
                $references[ $elem[$id_key] ] =& $references[ $elem[$pid_key] ]['children'][ $elem[$id_key] ];
            } else {
                $structure[ $elem[$pid_key] ][ $elem[$id_key] ] = $elem;
                $references[ $elem[$id_key] ] =& $structure[ $elem[$pid_key] ][ $elem[$id_key] ];
            }
        }
        return $structure;
    }
}
?>