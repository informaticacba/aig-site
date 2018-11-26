
<?php

$title = "Menu Type";
$security = ['minUserType' => 4 ];
require_once "bootstrap.php";
require_once RESOURCE_PATH . "/validate-signin.php";
?>


<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/6/2018
 * Time: 1:15 PM
 */

require_once  RESOURCE_PATH . "/database.php";
require_once  RESOURCE_PATH . "/mysqlidb.php";
require_once  KOOL_CONTROLS_PATH.'/KoolAjax/koolajax.php';
require_once  KOOL_CONTROLS_PATH."/KoolGrid/koolgrid.php";
require_once  KOOL_CONTROLS_PATH."/KoolGrid/ext/datasources/MySQLiDataSource.php";

$koolajax->scriptFolder = R_KOOL_CONTROLS_PATH."/KoolAjax";

$ds = new MySQLiDataSource($iDbCon);
$ds->SelectCommand = "SELECT * FROM menu_type ORDER BY type_code";
$ds->UpdateCommand = "UPDATE menu_type 
                          SET 
                            type_id ='@type_id',type_code='@type_code', 
                            type_description='@type_description',is_available_online='@is_available_online'
                          WHERE 
                            type_id='@type_id'";
$ds->InsertCommand = "INSERT INTO 
                            menu_type(type_id,type_code,type_description,'is_available_online') 
                          VALUES 
                            ('@type_id','@type_code','@type_description','@is_available_online')";

$ds->DeleteCommand = "DELETE FROM menu_type where type_id='@type_id'";

$grid = new KoolGrid("menuTypeGrid");
$grid->scriptFolder = R_KOOL_CONTROLS_PATH."/KoolGrid";
$grid->styleFolder  = "office2010blue";

$grid->RowAlternative = true;
$grid->AllowSelecting = true;
$grid->AllowScrolling = true;
$grid->SingleColumnSorting = true;
$grid->AllowInserting = true;
$grid->AllowSorting = true;
$grid->AllowEditing = true;
$grid->AllowDeleting = true;

$grid->AjaxEnabled = true;
$grid->DataSource = $ds;
$grid->MasterTable->Pager = new GridPrevNextAndNumericPager();
$grid->Width = "100%";
$grid->ColumnWrap = true;

$column = new GridEditDeleteColumn();
$column->ShowDeleteButton = true;
$column->Align = "center";
$column->Width = "6rem";
$grid->MasterTable->AddColumn($column);

$column = new GridBoundColumn();
$column->DataField = "type_id";
$column->HeaderText = 'Id';
$column->ReadOnly = false;
$validator = new RegularExpressionValidator();
$validator->ValidationExpression = "/^([0-9])+$/";
$validator->ErrorMessage = "Please input an integer";
$column->AddValidator($validator);
$grid->MasterTable->AddColumn($column);

$column = new GridBoundColumn();
$column->DataField = "type_code";
$column->HeaderText = 'Code';
$column->ReadOnly = false;
//Add required field validator to make sure the input is not empty.
$validator = new RequiredFieldValidator();
$column->AddValidator($validator);
$grid->MasterTable->AddColumn($column);

$column = new GridBoundColumn();
$column->DataField = "type_description";
$column->HeaderText = 'Description';
$column->ReadOnly = false;
//Add required field validator to make sure the input is not empty.
$validator = new RequiredFieldValidator();
$column->AddValidator($validator);
$grid->MasterTable->AddColumn($column);

$column = new GridBooleanColumn();
$column->DataField = "is_available_online";
$column->HeaderText = 'Available Online ?';
$column->ReadOnly = false;
$column->TrueText = "1";
$column->FalseText = "0";
$column->UseCheckBox = true;
$grid->MasterTable->AddColumn($column);

//Set edit mode to "form"
$grid->MasterTable->EditSettings->Mode = "form";
$grid->MasterTable->EditSettings->InputFocus = "HideGrid";

//Show Function Panel
$grid->MasterTable->ShowFunctionPanel = true;
//Insert Settings
$grid->MasterTable->InsertSettings->Mode = "Form";
$grid->MasterTable->InsertSettings->InputFocus = "HideGrid";
$grid->MasterTable->InsertSettings->ColumnNumber = 1;

$grid->Process();
?>


<?php
require_once "header-html.php";
?>


<div class="container-flex form-container">

    <?php
        require_once "popup-header.html.php";
    ?>

    <article class= "group form-section" style="width:70%">
        <h2 class="header-underline group-title">Menu Type</h2>

        <form id="form1" method="post">
            <?php echo $koolajax->Render();?>
            <?php echo $grid->Render();?>
        </form>
    </article>
</div>


<?php
require_once "footer-html.php";
?>