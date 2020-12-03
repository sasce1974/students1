<?php
$title = "Board " . $board->name;
include $base_page;
?>
<div class="text-center"><h3><?php print $board->name; ?> Board</h3></div>
<table class="table table-striped">
    <tr>
        <form method="post" action="/students/create">
            <td>New student</td>
            <td colspan="2">
                <input type="hidden" name="token" value="<?php //print $_SESSION['token']; ?>">
                <input type="hidden" name="board_id" value="<?php echo $board->id; ?>">
                <input type="text" name="name" placeholder="Insert Student Name"
                       class="form-control"  value="">
            </td>
            <td>
                <button type="submit" class="btn btn-outline-secondary">Create</button>
            </td>
        </form>
    </tr>
    <tr><th>id</th><th>Name</th><th>Average grade</th><td>Delete student</td></tr>
    <?php
    //$user = new User();
    foreach($students as $student){
        ?>
        <tr>
            <td><?php print $student->id; ?></td>
            <td><a href="/boards/<?php print $board->id; ?>/students/<?php print $student->id; ?>"><?php print $student->name; ?></a></td>
            <td><?php print round($student->averageGrade, 2); ?></td>
            <td><form method="post" action="/students/<?php print $student->id; ?>/destroy">
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>
<div class="text-center">
    <?php
    if($board->id == 1){
        print "<a href=\"/board/1/export\" class=\"btn btn-primary\">Export to JSON</a>";
    }else{
        print "<a href=\"/board/2/export\" class=\"btn btn-primary\">Export to XML</a>";
    }
    ?>
</div>
