<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Buat Card Baru</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
  <div class="container">
    <h1>Buat Card Baru di Board: {{ $board->board_name }}</h1>

    <form method="POST" action="{{ route('teamlead.cards.store', $board->board_id) }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Judul Card</label>
        <input type="text" name="card_title" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control"></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Prioritas</label>
        <select name="priority" class="form-control" required>
          <option value="low">Low</option>
          <option value="medium">Medium</option>
          <option value="high">High</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Estimasi Jam</label>
        <input type="number" name="estimated_hours" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">Deadline</label>
        <input type="date" name="due_date" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">Assign ke Username (pisahkan dengan koma)</label>
        <input type="text" name="usernames" class="form-control" placeholder="contoh: dev1, designer1" required>
        <small class="text-muted">Masukkan username developer/designer, bisa lebih dari satu.</small>
      </div>

      <button type="submit" class="btn btn-success">Simpan</button>
    </form>
  </div>
</body>
</html>
