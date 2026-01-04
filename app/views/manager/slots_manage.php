<?php
$pageTitle = "Manager - Manage Slots";
$pageCss = ["manager-slots.css"];
$pageJs  = ["manager-slots.js"];
include __DIR__ . "/../layouts/header.php";
?>

<main class="container">
  <div class="card">
    <h1 class="page-title">Manage Slots</h1>
    <p class="muted">Add new slots and view the list (data will come later).</p>

    <form method="post" action="/index.php?c=manager&a=slotsManage">
      <div class="form-row">
        <label for="slot_date">Date</label>
        <input type="date" id="slot_date" name="slot_date" />
      </div>

      <div class="form-row">
        <label for="start_time">Start Time</label>
        <input type="time" id="start_time" name="start_time" />
      </div>

      <div class="form-row">
        <label for="end_time">End Time</label>
        <input type="time" id="end_time" name="end_time" />
      </div>

      <button class="btn btn-primary" type="submit">Add Slot</button>
    </form>

    <hr style="margin: 18px 0; border: none; border-top: 1px solid #eee;" />

    <h2 style="margin: 0; font-size: 18px;">Slots List (Placeholder)</h2>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Start</th>
            <th>End</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>2026-01-04</td>
            <td>04:00 PM</td>
            <td>05:00 PM</td>
            <td>
              <a class="btn btn-light" href="#">Edit</a>
              <a class="btn btn-danger" href="#" data-confirm="Delete this slot?">Delete</a>
            </td>
          </tr>
          <tr>
            <td>2026-01-04</td>
            <td>05:00 PM</td>
            <td>06:00 PM</td>
            <td>
              <a class="btn btn-light" href="#">Edit</a>
              <a class="btn btn-danger" href="#" data-confirm="Delete this slot?">Delete</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</main>

<?php include __DIR__ . "/../layouts/footer.php"; ?>
