<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Designer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
  <div class="container">
    <h1 class="mb-4">🎨 Dashboard Designer</h1>

    @if($cards->isEmpty())
      <div class="alert alert-info">Belum ada tugas untuk Anda.</div>
    @else
      <table class="table table-bordered bg-white shadow-sm">
        <thead>
          <tr>
            <th>Proyek</th>
            <th>Board</th>
            <th>Judul</th>
            <th>Prioritas</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($cards as $card)
            <tr>
              <td>{{ $card->board->project->project_name }}</td>
              <td>{{ $card->board->board_name }}</td>
              <td>{{ $card->card_title }}</td>
              <td>{{ ucfirst($card->priority) }}</td>
              <td>{{ ucfirst($card->status) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>
</body>
</html>
