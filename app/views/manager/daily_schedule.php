<?php
$pageTitle = "Manager - Daily Schedule";
$pageCss = ["manager-schedule.css"];
$pageJs  = ["manager-schedule.js"];
include __DIR__ . "/../layouts/header.php";
?>

<main class="container">
  <div class="card">
    <h1 class="page-title">Daily Schedule</h1>
    <p class="muted">Approved bookings for a selected date (placeholder for now).</p>

    <form method="get" action="/index.php">
      <input type="hidden" name="c" value="manager" />
      <input type="hidden" name="a" value="dailySchedule" />

      <div class="form-row">
        <label for="date">Select Date</label>
        <input type="date" id="date" name="date" />
      </div>

      <button class="btn btn-primary" type="submit">View</button>
    </form>

    <hr style="margin: 18px 0; border: none; border-top: 1px solid #eee;" />

    <h2 style="margin: 0; font-size: 18px;">Approved Bookings (Placeholder)</h2>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Customer</th>
            <th>Team</th>
            <th>Phone</th>
            <th>Time</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Rahim</td>
            <td>Team Tigers</td>
            <td>01XXXXXXXXX</td>
            <td>04:00 PM - 05:00 PM</td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</main>

<?php include __DIR__ . "/../layouts/footer.php"; ?>
