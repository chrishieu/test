<?php

if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/screen.php' );
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class PhotoContestModel {
    private $tableName; 
    public function __construct () {
        global $table_prefix;
        $this->tableName = $table_prefix.'photo_competition';
    }
  
    public function getAll() {
        global $wpdb;
        $list = $wpdb->get_results( $wpdb->prepare(
        "
            SELECT *
            FROM $this->tableName
        ", array()));
        
        return $list;
    }
    
    public function getList($start, $limit) {
        global $wpdb;
        $list = $wpdb->get_results( $wpdb->prepare(
        "
            SELECT *
            FROM $this->tableName ORDER BY id DESC LIMIT %d, %d 
        ", $start, $limit));
        
        return $list;
    }
    
    public function delete($id) {
        global $wpdb;
        $list = $wpdb->get_results( $wpdb->prepare(
        "
            DELETE
            FROM $this->tableName WHERE id = %d
        ", $id));
        
        return $list;
    }
    
    public function getById($id) {
        global $wpdb;
        $list = $wpdb->get_row( $wpdb->prepare(
        "
            SELECT *
            FROM $this->tableName WHERE id= %d
        ", $id));
        
        return $list;
    }
    
    public function getTotal() {
        global $wpdb;
        $result = $wpdb->get_var( "SELECT COUNT(*) FROM $this->tableName");
        
        return $result;
    }
    
}



/**
 * Create a new table class that will extend the WP_List_Table
 */
class FiveMedia_Photo_Result_List_Table extends WP_List_Table
{
    
      public function __construct() {

        parent::__construct(
            array(
                'singular' => 'singular_form',
                'plural'   => 'plural_form',
                'ajax'     => false
            )
        );

    }

/**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        
        $this->process_bulk_action();
        
        $photoContestModel = new PhotoContestModel();
        $perPage =50;
        $currentPage = $this->get_pagenum();
        $start = ($currentPage-1)*$perPage;
        $data = $this->table_data($start, $perPage);
        
        $currentPage = $this->get_pagenum();
        
        $this->set_pagination_args( array(
            'total_items' => $photoContestModel->getTotal(),
            'per_page'    => $perPage
        ) );
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }
    
    /**
      * Process our bulk actions
      * 
      * @since 1.2
      */
     function process_bulk_action() {        
         if ( 'delete' === $this->current_action() ) {
             global $wpdb;
             $list_ids = $_REQUEST['inputId'];
             $photoContestModel = new PhotoContestModel();
             foreach ( $list_ids as $id ) {
                 $id = absint($id);

                  $player = $photoContestModel->getById($id);


                  if ($player) {

                    // Update votes count
                    $photoIds = $player->votes;
                    $listPhoto = explode('-', $photoIds);

                    foreach ($listPhoto as $photoId) {
                      $vote = get_field('voting', $photoId);
                      if (!is_numeric(intval($vote))) {
                        $vote = 0;
                      }
                      $vote = $vote - 1;
                      update_field('voting', $vote, $photoId);
                    }


                    $photoContestModel->delete($player->id);
                  }
                }
         }
     }

    
    function get_bulk_actions() {
        $actions = array(
            'delete' => __( 'Delete' , 'visual-form-builder'),
        );

        return $actions;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'id'           => 'Id',
            'first_name'          => 'First Name',
            'last_name'       => 'Last Name',
            'email'        => 'Email',
            'vote'  => 'Vote',
            'is_confirm' => 'Confirmed',
            'time_created' => 'Date',
            'action' => 'Action'
        );

        return $columns;
    }
    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }
    
    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="inputId[]" value="%d" />',
            $item['id']
        );
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'id':
            case 'first_name':
            case 'last_name':
            case 'email':
            case 'vote':
            case 'time_created':
            case 'is_confirm':
            case 'action':
            
                return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }
    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data($start, $limit)
    {
        $data = array();
        $photoContestModel = new PhotoContestModel();
        $result = $photoContestModel->getLIst($start, $limit);
        foreach ($result as $value) {

            $link_delete_vote = wp_nonce_url(admin_url()."tools.php?page=photo-vote-delete&id=$value->id");
            $data[] = array(
                'id' => $value->id,
                'last_name'       => $value->last_name,
                'first_name' => $value->first_name,
                'email'    => $value->email ,
                'vote' => $value->votes,
                'is_confirm' => $value->is_confirm,
                'time_created' => $value->time_created,
                'action' => '<a href="'.$link_delete_vote.'">Delete vote</a>'
              
            );
        }
      

        return $data;
    }
}

class Fivemedia_Photo_Result_Wp_List_Table
{
    /**
     * Constructor will create the menu item
     */
    public function __construct()
    {
        add_action( 'admin_menu', array($this, 'add_menu_example_list_table_page' ));
    }
    
    /**
     * Menu item will allow us to load the page to display the table
     */
    public function add_menu_example_list_table_page()
    {
        add_submenu_page('tools.php', 'Photo Competition Result', 'Photo Competition Result', 'manage_options', 'photo-competition-results', array($this, 'list_table_page') );
        add_submenu_page(null, "Delete player", "Delete player", "manage_options", "photo-vote-delete", "photo_competition_add_page_callback");
        add_submenu_page(null, "Export Data player", "Export Data player", "manage_options", "photo-vote-export", "photo_competition_add_page_callback");

    }
    /**
     * Display the list table page
     *
     * @return Void
     */
    public function list_table_page()
    {
        $exampleListTable = new FiveMedia_Photo_Result_List_Table();
        $exampleListTable->prepare_items();
        ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                
                
                <form id="wpse-list-table-form" method="post">
                <h2>Photo Competition Result</h2>
                <a class="button primary-color" href="<?php echo admin_url()."tools.php?page=photo-vote-export"; ?>">Export Data</a>
                <?php $exampleListTable->display(); ?>
                </form>
            </div>
        <?php
    }
}
new Fivemedia_Photo_Result_Wp_List_Table();


function photo_competition_add_page_callback() {
  
}

/**
* Manage page  to display after executing action on BO
*
*/

add_action('admin_init', 'check_to_link_photo_contest', 1);
function check_to_link_photo_contest() {
    if ( isset($_GET['page']) ) {
        

        switch ($_GET['page']) {
            case 'photo-vote-delete':
                // Check WP nonce
                if (!check_admin_referer()) { return; }
                
                $photoContestModel = new PhotoContestModel();
                $player=null;
                if (isset($_GET['id'])){
                    $player = $photoContestModel->getById($_GET['id']);
                }
              
                if ($player) {
                  
                    // Update votes count
                    $photoIds = $player->votes;
                    $listPhoto = explode('-', $photoIds);

                    foreach($listPhoto as $photoId) {
                        $vote = get_field('voting', $photoId);
                        if (!is_numeric(intval($vote))) {
                            $vote = 0;
                        }
                        $vote = $vote - 1;
                        update_field( 'voting', $vote, $photoId );
                    }
                  
                  
                    $photoContestModel->delete($player->id);
                    wp_redirect( admin_url()."tools.php?page=photo-competition-results" );  
                    exit();
                }
                break;
            case 'photo-vote-export':
                $photoContestModel = new PhotoContestModel();
                $all_votes = $photoContestModel->getAll();
                $filename = "data_export_" . date("Y-m-d:H:i:s") . ".csv";


                $rows = array();
                foreach ($all_votes as $value) {
                    $rows[] = array(
                        $value->first_name,
                        $value->last_name,
                        $value->email,
                        $value->votes,
                        $value->ip_address,
                        $value->is_confirm,
                        $value->time_created
                    );
                }
            


                $now = gmdate("D, d M Y H:i:s");
                header("Expires: Tue, 03 May 2020 06:00:00 GMT");
                header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
                header("Last-Modified: {$now} GMT");

                // force download  
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");

                // disposition / encoding on response body
                header("Content-Disposition: attachment;filename={$filename}");
                header("Content-Transfer-Encoding: binary");

                $df = fopen("php://output", 'w');
                fputcsv($df, array('First Name', 'Last Name', 'Email', 'Votes', 'IP Address', 'Is Confirm', 'Created'));
                foreach ($rows as $row) {
                   fputcsv($df, $row);
                }
                fclose($df);
                die();

                break;
        }
    }
}
