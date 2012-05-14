<?php



require_once (TEMPLATEPATH . '/lib/popshop-orders-list-table.php');

$orders = new Popshop_Orders_List_Table();

$orders->prepare_items();

?>


<form id="orders" method="get">
    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
    <?php $orders->display() ?>
</form>



