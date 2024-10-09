document.addEventListener('DOMContentLoaded', function() {
    var subjectElement = document.getElementById('subject');
    if (subjectElement) {
        subjectElement.addEventListener('change', updateClassDropdown);
    }

    var getObjScoresBtn = document.getElementById('getObjScoresBtn');
    if (getObjScoresBtn) {
        getObjScoresBtn.addEventListener('click', function() {
            var rows = document.querySelectorAll('#resultsTableBody tr');

            rows.forEach(function(row) {
                var sub_id = row.querySelector('.sub').textContent;
                var classid = row.querySelector('.class').textContent;
                var termid = row.querySelector('.term').textContent;
                var session = row.querySelector('.session').textContent;
                var email = row.querySelector('.email').textContent;

                if (sub_id && classid && email && termid && session) {
                    populateExamObjFromHistory(row, sub_id, classid, email, termid, session);
                }
            });
        });
    }

    var postResultBtn = document.getElementById('postResultBtn');
    if (postResultBtn) {
        postResultBtn.addEventListener('click', function() {
            var resultsTableBody = document.getElementById('resultsTableBody');
            if (resultsTableBody) {
                var rows = resultsTableBody.querySelectorAll('tr');

                var students = [];
                rows.forEach(function(row, index) {
                    var student = {};
                    student.sid = row.querySelector('input[name^="sid"]').value;
                    student.rt = row.querySelector('input[name^="rt"]').value;
                    student.hass = row.querySelector('input[name^="hass"]').value;
                    student.ass1 = row.querySelector('input[name^="ass1"]').value;
                    student.ass2 = row.querySelector('input[name^="ass2"]').value;
                    student.cl1 = row.querySelector('input[name^="cl1"]').value;
                    student.cl2 = row.querySelector('input[name^="cl2"]').value;
                    student.cl3 = row.querySelector('input[name^="cl3"]').value;
                    student.mtt = row.querySelector('input[name^="mtt"]').value;
                    student.nt1 = row.querySelector('input[name^="nt1"]').value;
                    student.nt2 = row.querySelector('input[name^="nt2"]').value;
                    student.nt3 = row.querySelector('input[name^="nt3"]').value;
                    student.proj = row.querySelector('input[name^="proj"]').value;
                    student.examobj = row.querySelector('input[name^="examobj"]').value;
                    student.examtheory = row.querySelector('input[name^="examtheory"]').value;
                    student.ca = row.querySelector('input[name^="ca"]').value;
                    students.push(student);
                });

                var formData = new FormData();
                formData.append('session', document.getElementById('session').value);
                formData.append('term', document.getElementById('term').value);
                formData.append('sub_id', document.getElementById('subject').value);
                formData.append('class', document.getElementById('class').value);
                formData.append('staffid', '<?php echo $staffid; ?>');
                formData.append('students', JSON.stringify(students));

                fetch('post_results.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload(); // Reload the page after displaying the alert
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    }
});

function updateClassDropdown() {
    var subjectId = document.getElementById('subject').value;
    var staffId = <?php echo json_encode($staffid); ?>;

    if (subjectId) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'get_classes.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var classElement = document.getElementById('class');
                if (classElement) {
                    classElement.innerHTML = xhr.responseText;
                }
            }
        };
        xhr.send('sub_id=' + subjectId + '&staffid=' + staffId);
    } else {
        var classElement = document.getElementById('class');
        if (classElement) {
            classElement.innerHTML = '<option value="" disabled selected>Select Class</option>';
        }
    }
}

function populateTable(event) {
    event.preventDefault();

    var session = document.getElementById('session').value;
    var term = document.getElementById('term').value;
    var subjectId = document.getElementById('subject').value;
    var classId = document.getElementById('class').value;
    var staffId = '<?php echo $staffid; ?>';

    if (session && term && subjectId && classId) {
        // Open a new window for the second form
        var newWindow = window.open('enter_result.php', 'newWindow', 'width=800,height=600');

        newWindow.onload = function() {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'get_students.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var resultsTableBody = newWindow.document.getElementById('resultsTableBody');
                    if (resultsTableBody) {
                        resultsTableBody.innerHTML = xhr.responseText;
                        newWindow.addEventListenersToInputs(); // Add event listeners to new inputs in the new window
                    }
                } else if (xhr.readyState == 4) {
                    console.error('Failed to fetch students data:', xhr.statusText);
                }
            };
            xhr.send('session=' + session + '&term=' + term + '&subject=' + subjectId + '&class=' + classId + '&staffid=' + staffId);
        };
    } else {
        alert('Please fill in all fields');
    }
}


function addEventListenersToInputs() {
    var inputs = document.querySelectorAll('input[name^="rt"], input[name^="hass"], input[name^="ass1"], input[name^="ass2"], input[name^="cl1"], input[name^="cl2"], input[name^="cl3"], input[name^="mtt"], input[name^="nt1"], input[name^="nt2"], input[name^="nt3"], input[name^="proj"], input[name^="examobj"], input[name^="examtheory"]');
    inputs.forEach(function(input) {
        input.addEventListener('input', updateTable);
        input.addEventListener('input', function (event) {
                const maxValue = parseInt(event.target.max, 10);
                if (parseInt(event.target.value, 10) > maxValue) {
                    alert(`The value entered exceeds the maximum allowed value of ${maxValue}`);
                    event.target.value = maxValue;
                }
            });
    });
}

function updateTable() {
    var row = this.closest('tr');
    if (row) {
        // Get values
        var rt = parseFloat(row.querySelector('input[name^="rt"]').value) || 0;
        var hass = parseFloat(row.querySelector('input[name^="hass"]').value) || 0;
        var ass1 = parseFloat(row.querySelector('input[name^="ass1"]').value) || 0;
        var ass2 = parseFloat(row.querySelector('input[name^="ass2"]').value) || 0;
        var cl1 = parseFloat(row.querySelector('input[name^="cl1"]').value) || 0;
        var cl2 = parseFloat(row.querySelector('input[name^="cl2"]').value) || 0;
        var cl3 = parseFloat(row.querySelector('input[name^="cl3"]').value) || 0;
        var mtt = parseFloat(row.querySelector('input[name^="mtt"]').value) || 0;
        var nt1 = parseFloat(row.querySelector('input[name^="nt1"]').value) || 0;
        var nt2 = parseFloat(row.querySelector('input[name^="nt2"]').value) || 0;
        var nt3 = parseFloat(row.querySelector('input[name^="nt3"]').value) || 0;
        var proj = parseFloat(row.querySelector('input[name^="proj"]').value) || 0;
        var examobj = parseFloat(row.querySelector('input[name^="examobj"]').value) || 0;
        var examtheory = parseFloat(row.querySelector('input[name^="examtheory"]').value) || 0;

        // Calculate averages
        var assAvg = ((hass + ass1 + ass2) / 3).toFixed(1);
        var clAvg = ((cl1 + cl2 + cl3) / 3).toFixed(1);
        var ntAvg = ((nt1 + nt2 + nt3) / 3).toFixed(1);

        // Update average cells
        row.querySelector('.ass-avg').textContent = assAvg;
        row.querySelector('.cl-avg').textContent = clAvg;
        row.querySelector('.nt-avg').textContent = ntAvg;

        // Calculate CA
        var ca = (rt + parseFloat(assAvg) + parseFloat(clAvg) + mtt + parseFloat(ntAvg) + proj).toFixed(1);
        if (ca > 40) {
            alert('The sum of R.T, ASS AVG, CL AVG, M.T.T, N.T AVG, and PROJ must not exceed 40.');
            row.querySelector('input[name^="ca"]').value = '';
            return;
        }

        // Update CA cell
        row.querySelector('input[name^="ca"]').value = ca;

        // Calculate total
        var total = (parseFloat(ca) + examobj + examtheory).toFixed(1);
        row.querySelector('.total').textContent = total;

        // Update grade
        var gradeCell = row.querySelector('.grade');
        if (gradeCell) {
            var classValue = document.getElementById('class').value.toLowerCase();

            var grade;

            if (classValue.includes('jss1') || classValue.includes('jss2') || classValue.includes('jss3')) {
                if (total >= 80) {
                    grade = 'A';
                    gradeCell.style.color = 'green';
                } else if (total >= 70) {
                    grade = 'B';
                    gradeCell.style.color = 'green';
                } else if (total >= 60) {
                    grade = 'C';
                    gradeCell.style.color = 'green';
                } else if (total >= 50) {
                    grade = 'P';
                    gradeCell.style.color = 'black';
                } else {
                    grade = 'F';
                    gradeCell.style.color = 'red';
                }
            } else if (classValue.includes('ss1') || classValue.includes('ss2') || classValue.includes('ss3')) {
                if (total >= 85) {
                    grade = 'A1';
                    gradeCell.style.color = 'green';
                } else if (total >= 80) {
                    grade = 'B2';
                    gradeCell.style.color = 'green';
                } else if (total >= 75) {
                    grade = 'B3';
                    gradeCell.style.color = 'green';
                } else if (total >= 70) {
                    grade = 'C4';
                    gradeCell.style.color = 'green';
                } else if (total >= 65) {
                    grade = 'C5';
                    gradeCell.style.color = 'green';
                } else if (total >= 60) {
                    grade = 'C6';
                    gradeCell.style.color = 'green';
                } else if (total >= 55) {
                    grade = 'D7';
                    gradeCell.style.color = 'black';
                } else if (total >= 50) {
                    grade = 'E8';
                    gradeCell.style.color = 'black';
                } else {
                    grade = 'F9';
                    gradeCell.style.color = 'red';
                }
            }

            gradeCell.textContent = grade;
        }
    }
}