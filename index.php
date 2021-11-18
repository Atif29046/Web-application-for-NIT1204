<!-- Database Connection (include) -->
<!-- Student Name: Muhammad Atif -->
<!-- Student ID: s4652595 -->
<?php include('dbconn.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- CSS -->
	<link rel="stylesheet" href="css/styles.css">

	<title>Shopping List Manager</title>
</head>

<?php

	$records = updateList();
	
	// Fetch records from database
	function updateList(){
		$query = "SELECT * FROM shoppinglist";
		global $conn;
		$records = array();
		$result = $conn->query($query);

		if($result->num_rows>0){
			while($row=$result->fetch_assoc()){
				array_push($records,$row['item']);
			}
		}

		return $records;
	}

	// Switch case
	switch (true) {

		// to modify an item
		case isset($_POST['save']):
			$item = $_POST['modifyText'];
			$old = $_POST['old'];
			
			$query = "UPDATE shoppinglist SET item=? WHERE item=?";

			$sql = $conn->prepare($query);
			$sql->bind_param("ss",$item,$old);
			$sql->execute();
			$records = updateList();
			break;
		
		// to delete an item
		case isset($_POST['delete']):
			$item = $_POST['selectItem'];
			if(strlen($item)>0)
			{
				$query = "DELETE FROM shoppinglist WHERE item=?";
				$sql = $conn->prepare($query);
				$sql->bind_param("s",$item);
				$sql->execute();
				$records = updateList();
			}
			break;

		// to add an item
		case isset($_POST['addItem']):
			$item = ucfirst($_POST['addText']);
			if(!in_array($item,$records)){
				array_push($records,$item);

				$query = "INSERT INTO shoppinglist(item)VALUES(?)";
				$sql = $conn->prepare($query);
				$sql->bind_param("s",$item);
				$sql->execute();
			}
			
			break;

		// to sort the list
		case isset($_POST['sort']):
			$records = updateList();
			sort($records);
			break;
			
	}
				

?>

<!-- body START -->
<body>
	<!-- div START -->
	<div class="container">

		<!-- div START -->
		<div class="header">
			<h1>Shopping List Manager</h1>
			<hr>
		</div>
		<!-- div END -->

		<!-- div START -->
		<div class="items">
			<h2>Items:</h2>
			<ol type="1">
				<?php 
				
				
				if(sizeof($records)>0){
					foreach($records as $record){
						echo '<li>'.$record.'</li>';
					}
				}
				else{
					echo '<h3>There are no items in the list.</h3>';
				}
				
			?>
			</ol>
		</div>
		<!-- div END -->

		<!-- div START -->
		<div class="add">
			<h2>Add Item:</h2>

			<!-- form START -->
			<form action="" method="POST">
				<label for="addText">Item:</label>
				<input type="text" id="addText" name="addText" />
				<input type="submit" id="addItem" name="addItem" Value="Add Item"/>
			</form>
			<!-- form END -->
		</div>
		<!-- div END -->
<?php
	$style1 = '';
	$style2 = 'none';
	$oldItem = '';

	if (isset($_POST['modify'])) {
		if(strlen($_POST['selectItem'])>0){
			
			$style1 = 'none';
			$style2 = 'block';
			
			$oldItem = $_POST["selectItem"];
		}	
	}

	if (isset($_POST['cancel'])) {
		$style1 = 'block';
		$style2 = 'none';
		$_POST["selectItem"] = '';
	}
?>
		<!-- div START -->
		<div class="select" id="select" style="display:<?php echo $style1;?>">
			<h2>Select Item:</h2>
			<!-- form START -->
			<form action="" method="POST">
				<label for="selectItem">Task:</label>
				<!-- select START -->
				<select name="selectItem" id="selectItem">
					
				<?php 
						if(sizeof($records)>0){
							foreach($records as $record){
								echo '<option value="'.$record.'">'.$record.'</option>';
							}
						}
						else{
							echo '<option></option>';
						}
						
					?>
				<input type="submit" name="modify" value="Modify item"/>
				<input type="submit" name="delete" value="Delete item" />
			</form>
			<!-- form END -->

			<?php 
				if(sizeof($records)>=2){
					
					echo '<form action="" method="post">
						<input type="submit" name="sort" value="Sort item" />
					</form>';
				}
			?>
				
		</select>
		<!-- select END -->
		</div>
		<!-- div END -->

		<!-- div START -->
		<div class="modify" id="modify"  style="display:<?php echo $style2;?>" >
			<h2>Item to Modify:</h2>

			<!-- form START -->
			<form action="" method="POST">
				<label for="modifyItem">Item:</label>
				<input type="text" name="modifyText" id="modifyText" value="<?php echo $oldItem;?>"/>
				<input type="hidden" name="old" value="<?php echo $oldItem;?>"/>
				<input type="submit" name="save" value="Save Changes" />
				<input type="submit" name="cancel" value="Cancel Changes" />
			</form>
			<!-- form END -->
		</select>
		<!-- select END -->
		</div>
		<!-- div END -->
	</div>
	<!-- div END -->
</body>
<!-- body END -->
</html>
<!-- html END -->