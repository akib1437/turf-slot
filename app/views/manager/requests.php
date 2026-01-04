<?php
$pageTitle = "Manager - Booking Requests";
$pageCss = ["manager-requests.css"];
$pageJs  = ["manager-requests.js"];
include __DIR__ . "/../layouts/header.php";
?>

<main class="container">
  <div class="card">
    <h1 class="page-title">Booking Requests</h1>
    <p class="muted">Pending requests will show here (placeholder data for now).</p>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Customer</th>
            <th>Team Name</th>
            <th>Phone</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Rahim</td>
            <td>Team Tigers</td>
            <td>01XXXXXXXXX</td>
            <td>2026-01-05</td>
            <td>04:00 PM - 05:00 PM</td>
            <td>Pending</td>
            <td>
              <a class="btn btn-primary" href="#" data-confirm="Approve this request?">Approve</a>
              <a class="btn btn-danger" href="#" data-confirm="Reject this request?">Reject</a>
            </td>
          </tr>
          <tr>
            <td>Karim</td>
            <td>Team Eagles</td>
            <td>01XXXXXXXXX</td>
            <td>2026-01-05</td>
            <td>05:00 PM - 06:00 PM</td>
            <td>Pending</td>
            <td>
              <a class="btn btn-primary" href="#" data-confirm="Approve this request?">Approve</a>
              <a class="btn btn-danger" href="#" data-confirm="Reject this request?">Reject</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</main>

<?php include __DIR__ . "/../layouts/footer.php"; ?>
