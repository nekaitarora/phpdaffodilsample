<?php
include_once("../model/workflow_model.php");
include_once('../../config/include.php');
include('../../config/db.php');
$workFlow = new WorkFlow();
// Get Record
if ($_POST['type'] == 'edit') {
    $data['id'] = $_POST['id'];
    $saveData = $workFlow->getWFById($data);
    echo json_encode($saveData, JSON_PRETTY_PRINT);
}
// Delete
if ($_POST['type'] == 'delete') {
    $data['id'] = $_POST['id'];
    if ($workFlow->deleteWFById($data)) {
        echo "Work flow deleted successfully.";
    } else {
        echo "Work flow not deleted.";
    }
}

// Add
if (isset($_REQUEST['wfname'])) {
    if ($_REQUEST['wfname'] == '') {
        echo "Please provide work flow name.";
        die;
    }
    if ($_REQUEST['wfdesc'] == '') {
        echo "Please provide work flow description.";
        die;
    }
    // Save Workflow
    $data['wfname'] = $_REQUEST['wfname'];
    $data['wfdesc'] = $_REQUEST['wfdesc'];

    $workFlow = new WorkFlow();
    $saveData = $workFlow->saveWorkFlow($data);
    if ($saveData) {
        echo "success";
        die;
    } else {
        echo "error";
        die;
    }
}
if (isset($_REQUEST['actionfunction']) && $_REQUEST['actionfunction'] != '') {

    $actionfunction = $_REQUEST['actionfunction'];

    call_user_func($actionfunction, $_REQUEST, $con, $limit, $adjacent);
}

function showData($data, $con, $limit, $adjacent) {
    $page = $data['page'];
    if ($page == 1) {
        $start = 0;
    } else {
        $start = ($page - 1) * $limit;
    }
    $workFlow = new WorkFlow();
    $rows = $workFlow->cntWFList();

    $selOpt1 = $workFlow->getWFList($start, $limit);
    foreach ($selOpt1 as $key => $val) {
        ?>
        <ul style="float: left; display: inline; width: 100%;">
            <li style="float: left; width: 5%;"><?php echo $key + 1; ?>)</li>
            <li style="float: left; width: 20%; padding-left: 10px;"><?php echo ucfirst($val['wf_name']); ?></li>
            <li style="float: left; width: 20%; padding-left: 100px;"><?php echo $val['wf_created']; ?></li>
            <li style="float: left; padding-left: 100px;"><a href="workflow.php?id=<?php echo $val['wf_id']; ?>">[View]</a> / <a href="#" onclick="popup('popUpDiv', 'edit', < ?php echo $val['wf_id']; ? > )">[Edit]</a> / <a href="#" onclick="deleteWF( < ?php echo $val['wf_id']; ? > )">[Delete]</a></li>
        </ul>
        <?php
    }
    pagination($limit, $adjacent, $rows, $page);
}

function pagination($limit, $adjacents, $rows, $page) {
    $pagination = '';
    if ($page == 0)
        $page = 1;     //if no page var is given, default to 1.
    $prev = $page - 1;       //previous page is page - 1
    $next = $page + 1;       //next page is page + 1
    $prev_ = '';
    $first = '';
    $lastpage = ceil($rows / $limit);
    $next_ = '';
    $last = '';
    if ($lastpage > 1) {

        //previous button
        if ($page > 1)
            $prev_.= "<a class='page-numbers' href=\"?page=$prev\">previous</a>";
        else {
            //$pagination.= "<span class=\"disabled\">previous</span>";	
        }

        //pages	
        if ($lastpage < 5 + ($adjacents * 2)) { //not enough pages to bother breaking it up
            $first = '';
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page)
                    $pagination.= "<span class=\"current\">$counter</span>";
                else
                    $pagination.= "<a class='page-numbers' href=\"?page=$counter\">$counter</a>";
            }
            $last = '';
        }
        elseif ($lastpage > 3 + ($adjacents * 2)) { //enough pages to hide some
            //close to beginning; only hide later pages
            $first = '';
            if ($page < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a class='page-numbers' href=\"?page=$counter\">$counter</a>";
                }
                $last.= "<a class='page-numbers' href=\"?page=$lastpage\">Last</a>";
            }

            //in middle; hide some front and some back
            elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                $first.= "<a class='page-numbers' href=\"?page=1\">First</a>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a class='page-numbers' href=\"?page=$counter\">$counter</a>";
                }
                $last.= "<a class='page-numbers' href=\"?page=$lastpage\">Last</a>";
            }
            //close to end; only hide early pages
            else {
                $first.= "<a class='page-numbers' href=\"?page=1\">First</a>";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a class='page-numbers' href=\"?page=$counter\">$counter</a>";
                }
                $last = '';
            }
        }
        if ($page < $counter - 1)
            $next_.= "<a class='page-numbers' href=\"?page=$next\">next</a>";
        else {
            //$pagination.= "<span class=\"disabled\">next</span>";
        }
        $pagination = "<div class=\"pagination\">" . $first . $prev_ . $pagination . $next_ . $last;
        //next button

        $pagination.= "</div>\n";
    }

    echo $pagination;
}
?>