{{--

PENDING INQUIRIES BLADE FILE - CODE SUMMARY


LINE 1-19: Document setup - HTML5 doctype, head section with meta tags, title, 
           external CSS (Font Awesome, custom emp.css, Bootstrap)

LINE 20-49: Header section - Logo, toggle button for sidebar, session selector,
            notification bell, user dropdown menu with profile and login options

LINE 50-51: Main container div starts

LINE 52-233: Left Sidebar Navigation
  - LINE 52-58: Sidebar container and admin info display
  - LINE 60-233: Bootstrap accordion menu with 9 collapsible sections:
    * LINE 61-75: User Management (Employee, Batches Assignment)
    * LINE 76-99: Master (Courses, Batches, Scholarship, Fees, Branch)
    * LINE 100-114: Session Management (Session, Calendar, Student Migrate)
    * LINE 115-131: Student Management (Inquiry, Onboard, Pending Fees, Students)
    * LINE 132-142: Fees Management (Fees Collection)
    * LINE 143-155: Attendance Management (Student, Employee)
    * LINE 156-168: Study Material (Units, Dispatch Material)
    * LINE 169-179: Test Series Management (Test Master)
    * LINE 180-200: Reports (Walk In, Attendance, Test Series, Inquiry, Onboard)

LINE 234-252: Right Content Area Header
  - LINE 236-238: Page title "Pending Inquiries"
  - LINE 239-246: Action buttons

LINE 253-282: Table Controls
  - LINE 254-268: Show entries dropdown (10, 25, 50, 100 options)
  - LINE 269-274: Search input field with icon

LINE 275-295: Onboarding Table Structure
  - LINE 276-286: Table headers 
  - LINE 287-289: Empty tbody tag
  - LINE 290-294: Comment indicating modal fillables location

LINE 296-338: Dynamic Employee Table Rows (Blade foreach loop)
  - Displays user data from database
  - Status badge with color coding
  - Action dropdown with 4 options: View, Edit, Transfer, History

LINE 340-342: Comment for options modals section

LINE 344-375: View Modal (foreach loop for each user)
  - Read-only display of employee details
  - Shows: Name, Email, Mobile, Alternate Mobile, Branch, Department

LINE 377-445: Edit Modal (foreach loop for each user)
  - LINE 379-382: PHP variables setup for current department and roles
  - LINE 384-443: Edit form with PUT method

  LINE 481-498: Footer Section
  - LINE 482-484: Pagination info text
  - LINE 485-493: Pagination controls (Previous, page numbers, Next)

LINE 499-500: Closing divs for main container


LINE 622-624: Closing divs and body tag

LINE 625-628: External JavaScript includes (Bootstrap bundle, emp.js, jQuery)

LINE 629-665: AJAX Script for Dynamic User Addition
  - Prevents page reload on form submit
  - Handles form validation errors
  - Appends new user to table without refresh
--}}

<!DOCTYPE html>


<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pending Fees Students</title>
       <link rel="stylesheet" href="{{ asset(path: 'css/studymate.css') }}">

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <!-- Custom CSS -->
  <link rel="stylesheet" href="{{asset('css/emp.css')}}">
   <!-- Bootstrap 5.3.6 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

</head>

<body>
  <!-- Header Section: Contains logo, sidebar toggle, session selector, notifications, and user menu -->
 
  <div class="header">
    <div class="logo">
      <img src="{{asset('images/login.png')}}" class="img">

      <!-- Sidebar toggle button -->
      <button class="toggleBtn" id="toggleBtn"><i class="fa-solid fa-bars"></i></button>
    </div>
    <div class="pfp">
      <div class="session">
        <h5>Session:</h5>
        <select>
          <option>2024-2025</option>
          <option>2026</option>
        </select>
      </div>
      <i class="fa-solid fa-bell"></i>
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button" data-bs-toggle="dropdown"
          aria-expanded="false">
          <i class="fa-solid fa-user"></i>
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="{{route('profile.index') }}""> <i class="fa-solid fa-user"></i>Profile</a></li>
          <li><a class="dropdown-item"><i class="fa-solid fa-arrow-right-from-bracket"></i>Log In</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="main-container">
 <!-- Left Sidebar: Navigation menu with collapsible accordion sections -->
   <div class="left" id="sidebar">

      <div class="text" id="text">
        <h6>ADMIN</h6>
        <p>Institute Email</p>
      </div>

      <!-- Left side bar accordian -->
     <div class="accordion accordion-flush" id="accordionFlushExample">

  <!-- User Management -->
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne"
        id="accordion-button">
        <i class="fa-solid fa-user-group" id="side-icon"></i>Team Control
      </button>
    </h2>
    <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <ul class="menu" id="dropdown-body">
          <li><a class="item" href="{{ route('user.emp.emp') }}"><i class="fa-solid fa-user" id="side-icon"></i>Staff Directory</a></li>     
          <!-- <li><a class="item" href="{{ route('user.batches.batches') }}"><i class="fa-solid fa-user-group" id="side-icon"></i> Batches Assignment</a></li> -->
        </ul>
      </div>
    </div>
  </div>

  <!-- Master -->
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo"
        id="accordion-button">
        <i class="fa-solid fa-user-group" id="side-icon"></i>Academy Setting 
      </button>
    </h2>
    <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <ul class="menu" id="dropdown-body">
          <li><a class="item" href="{{ route('courses.index') }}"><i class="fa-solid fa-book-open" id="side-icon"></i>Course Catalog</a></li>
          <li><a class="item" href="{{ route('batches.index') }}"><i class="fa-solid fa-user-group fa-flip-horizontal" id="side-icon"></i>Batch Planner</a></li>
          <li><a class="item" href="{{ route('master.scholarship.index') }}"><i class="fa-solid fa-graduation-cap" id="side-icon"></i>Scholarships Schemes</a></li>
          <li><a class="item" href="{{ route('fees.index') }}"><i class="fa-solid fa-credit-card" id="side-icon"></i>Fees Structures</a></li>
          <!-- <li><a class="item" href="{{ route('master.other_fees.index') }}"><i class="fa-solid fa-wallet" id="side-icon"></i> Other Fees Master</a></li> -->
          <li><a class="item" href="{{ route('branches.index') }}"><i class="fa-solid fa-diagram-project" id="side-icon"></i>Centres</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Session Management -->
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree"
        id="accordion-button">
        <i class="fa-solid fa-user-group" id="side-icon"></i>Academic Cycle 
      </button>
    </h2>
    <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <ul class="menu" id="dropdown-body">
          <li><a class="item" href="{{ route('sessions.index') }}"><i class="fa-solid fa-calendar-day" id="side-icon"></i>Academic Session</a></li>
          <li><a class="item" href="{{ route('calendar.index') }}"><i class="fa-solid fa-calendar-days" id="side-icon"></i>Institute Calendar</a></li>
          <!-- <li><a class="item" href="#"><i class="fa-solid fa-user-check" id="side-icon"></i> Student Migrate</a></li> -->
        </ul>
      </div>
    </div>
  </div>

  <!-- Student Management -->
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour"
        id="accordion-button">
        <i class="fa-solid fa-user-group" id="side-icon"></i>Student Lifecycle
      </button>
    </h2>
    <div id="flush-collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <ul class="menu" id="dropdown-body">
          <li><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i>Leads and Inquiries</a></li>
          <li><a class="item" href="{{ route('student.student.pending') }}"><i class="fa-solid fa-user-check" id="side-icon"></i>New Admissions</a></li>
          <li><a class="item" href="{{ route('student.pendingfees.pending') }}"><i class="fa-solid fa-user-check" id="side-icon"></i>Pending Payments</a></li>
          <li><a class="item" href="{{ route('smstudents.index') }}"><i class="fa-solid fa-user-check" id="side-icon"></i>Student Directory</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Fees Management -->
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive"
        id="accordion-button">
        <i class="fa-solid fa-credit-card" id="side-icon"></i>Finance Desk
      </button>
    </h2>
    <div id="flush-collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <ul class="menu" id="dropdown-body">
          <li><a class="item" href="{{ route('fees.management.index') }}"><i class="fa-solid fa-credit-card" id="side-icon"></i>Fee Collection Desk</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Attendance Management -->
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix"
        id="accordion-button">
        <i class="fa-solid fa-user-check" id="side-icon"></i> Attendance Hub
      </button>
    </h2>
    <div id="flush-collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <ul class="menu" id="dropdown-body">
          <li><a class="item" href="{{ route('attendance.employee.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i>Staff Attendance</a></li>
          <li><a class="item" href="{{ route('attendance.student.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i> Student Attendance</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Study Material -->
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseSeven" aria-expanded="false" aria-controls="flush-collapseSeven"
        id="accordion-button">
        <i class="fa-solid fa-book-open" id="side-icon"></i>Learning Resources
      </button>
    </h2>
    <div id="flush-collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <ul class="menu" id="dropdown-body">
          <li><a class="item" href="{{ route('units.index') }}"><i class="fa-solid fa-user" id="side-icon"></i>Material Distribution</a></li>
          <!-- <li><a class="item" href="{{ route('study_material.dispatch.index') }}"><i class="fa-solid fa-user" id="side-icon"></i>Dispatch Material</a></li> -->
        </ul>
      </div>
    </div>
  </div>

  <!-- Test Series Management -->
  <!-- <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseEight" aria-expanded="false" aria-controls="flush-collapseEight"
        id="accordion-button">
        <i class="fa-solid fa-chart-column" id="side-icon"></i> Test Series Management
      </button>
    </h2>
    <div id="flush-collapseEight" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <ul class="menu" id="dropdown-body">
          <li><a class="item" href=" "><i class="fa-solid fa-user" id="side-icon"></i>Test Master</a></li>
        </ul>
      </div>
    </div>
  </div> -->

  <!-- Reports -->
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseNine" aria-expanded="false" aria-controls="flush-collapseNine"
        id="accordion-button">
        <i class="fa-solid fa-square-poll-horizontal" id="side-icon"></i>Reports and Insights
      </button>
    </h2>
    <div id="flush-collapseNine" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <ul class="menu" id="dropdown-body">
          <li><a class="item" href="{{ route('reports.walkin.index') }}"><i class="fa-solid fa-user" id="side-icon"></i>Statistics</a></li>
          <!-- <li><a class="item" href="#"><i class="fa-solid fa-calendar-days" id="side-icon"></i> Attendance</a></li>
          <li><a class="item" href="#"><i class="fa-solid fa-file" id="side-icon"></i>Test Series</a></li>
          <li><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-file" id="side-icon"></i>Inquiry History</a></li>
          <li><a class="item" href="#"><i class="fa-solid fa-file" id="side-icon"></i>Onboard History</a></li> -->
        </ul>
      </div>
    </div>
  </div>
</div>
    </div>

    <div class="right" id="right">
      <div class="top">
        <div class="top-text">
        </div>
            <div class="btns">
               <a href="{{ route('student.student.pending') }}"><button type="button" class="pendingbtn">Pending Inquiries</button></a>
              <a class="item" href="{{ route('student.onboard.onboard') }}"><button type="button" class="onboardbtn">Onboarding Students</button></a>
            </div>

      </div>
      <div class="whole">
         <!-- Table controls: entries dropdown and search -->
        <div class="dd">
          <div class="line">
            <h6>Show Enteries:</h6>
            <div class="dropdown">
             <button class="btn btn-secondary dropdown-toggle" id="number" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            {{ request('per_page', 10) }}
          </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item">10</a></li>
                <li><a class="dropdown-item">25</a></li>
                <li><a class="dropdown-item">50</a></li>
                <li><a class="dropdown-item">100</a></li>
              </ul>
            </div>
          </div>
          <div class="search">
            <h4 class="search-text">Search</h4>
            <input type="search" placeholder="" class="search-holder" required>
            <i class="fa-solid fa-magnifying-glass"></i>
          </div>
        </div>
        <table class="table table-hover" id="table">
          <thead>
            <tr>
              <th scope="col" id="one">Serial No.</th>
              <th scope="col" id="one">Student Name</th>
              <th scope="col" id="one">Father Name</th>
              <th scope="col" id="one">Father Contact No.</th>
              <th scope="col" id="one">Course Name</th>
              <th scope="col" id="one">Delivery Mode</th>
              <th scope="col" id="one">Course Content</th>
              <th scope="col" id="one">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
            </tr>
          </tbody>
<!-- Modal fillables where roles are assigned according to dept automatically -->

      @foreach($students as $index => $student)
<tr>
   <!-- Serial number (index + 1) -->
  <td>{{ $index + 1 }}</td>
  <td>{{ $student->name }}</td>
  <td>{{ $student->father }}</td>
  <td>{{ $student->mobileNumber ?? '—' }}</td>
<td>{{ $student->courseName ?? '—' }}</td>
<td>{{ $student->deliveryMode ?? '—' }}</td>
<td>{{ $student->courseContent ?? '—' }}</td>
  <td>
    <div class="dropdown">
      <button class="btn btn-primary dropdown-toggle" type="button" id="actionMenuButton"
              data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-three-dots-vertical" style="color: #000000;"></i>
      </button>
      <ul class="dropdown-menu" aria-labelledby="actionMenuButton">
        <li>
           <a href="{{ route('student.student.edit', $student->_id) }}">
<button class="dropdown-item">
            Edit Details
          </button></a>
        </li>
      </ul>
    </div>
  </td>
</tr>
@endforeach

        </table>



      </div>
      <div class="footer">
        <div class="left-footer">
          <p>Showing 1 to 10 of 10 Enteries</p>
        </div>
        <div class="right-footer">
          <nav aria-label="...">
            <ul class="pagination">
              <li class="page-item"><a href="#" class="page-link" id="pg1">Previous</a></li>
              <li class="page-item active">
                <a class="page-link" href="#" aria-current="page" id="pg2">1</a>
              </li>
              <li class="page-item"><a class="page-link" href="/user management/emp/emp2.html" id="pg3">2</a></li>
              <li class="page-item"><a class="page-link" href="#" id="pg1">Next</a></li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
  </div>
</body>
<!-- External JavaScript Libraries -->
<!-- Bootstrap Bundle JS (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
<script src="{{asset('js/emp.js')}}"></script>


<!-- AJAX Script: Handles dynamic user addition without page reload -->
<script>
// Event handler for add user form submission
  // Ajax for dynamic user addition without page reload
  $('#addUserForm').on('submit', function (e) {
    // Prevent default form submission behavior
    e.preventDefault();
    // Clear previous error messages
    $('.text-danger').text('');


    // AJAX POST request to add user
    $.ajax({
      url: "{{ route('users.add') }}",
      method: 'POST',
      data: $(this).serialize(),
      success: function (response) {
        // On successful user addition
        if (response.status === 'success') {
          // Close the modal
          $('#addUserModal').modal('hide');
          // Reset form fields
          $('#addUserForm')[0].reset();

          // Dynamically append new user row to table without page reload
          // Append user to table
          $('#users-table tbody').append(`
                    <tr>
                        <td>${response.user.name}</td>
                        <td>${response.user.email}</td>
                        <td>${response.user.phone}</td>
                    </tr>
                `);
        }
      },
      error: function (xhr) {
        // Handle validation errors (HTTP 422)
        if (xhr.status === 422) {
          const errors = xhr.responseJSON.errors;
          // Display error messages for each field
          for (let field in errors) {
            $(#error-${field}).text(errors[field][0]);
          }
        }
      }
    });
  });
</script>

</html>