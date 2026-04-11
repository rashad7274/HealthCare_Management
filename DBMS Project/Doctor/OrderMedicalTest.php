<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Medical Tests</title>

<style>
body{
    font-family: Arial, sans-serif;
    background-color:#f2f6f8;
    margin:0;
    padding:0;
    text-align:center;
}

header{
    background-color:#d6ecff;
    padding:20px;
}

.form-container{
    width:420px;
    margin:40px auto;
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0px 2px 5px rgba(0,0,0,0.1);
    text-align:left;
}

input, select{
    width:100%;
    padding:10px;
    margin:10px 0;
}

button{
    width:100%;
    padding:10px;
    background:#4da6ff;
    color:white;
    border:none;
    cursor:pointer;
}

button:hover{
    background:#3399ff;
}
</style>
</head>

<body>

<header>
<h1>Order Medical Tests</h1>
</header>

<div class="form-container">

<form action="save_test.php" method="POST">

    <label>Patient Name</label>
    <input type="text" name="patient_name" required>

    <label>Select Test</label>
    <select name="test_name" required>
        <option>Blood Test</option>
        <option>X-Ray</option>
        <option>MRI Scan</option>
        <option>CT Scan</option>
        <option>Urine Test</option>
    </select>

    <button type="submit">Submit Test Request</button>

</form>

</div>

</body>
</html>