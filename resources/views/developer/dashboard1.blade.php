<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Developer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      padding-top: 70px;
    }
    .table thead th {
      vertical-align: middle;
    }
    .badge-low {
      background-color: #6c757d;
    }
  </style>
</head>
<body class="bg-light">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">👨‍💻 Dashboard Developer</a>
    </div>
  </nav>

  <div class="container">

    <!-- Alert Messages -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Table -->
    @if($cards->isEmpty())
      <div class="alert alert-info mt-4">Belum ada tugas untuk Anda.</div>
    @else
      <div class="table-responsive mt-4">
        <table class="table table-hover table-bordered bg-white shadow-sm align-middle">
          <thead class="table-light">
            <tr>
              <th>Proyek</th>
              <th>Board</th>
              <th>Judul</th>
              <th>Prioritas</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($cards as $card)
              <tr>
                <td>{{ $card->board->project->project_name }}</td>
                <td>{{ $card->board->board_name }}</td>
                <td>{{ $card->card_title }}</td>
                <td>
                  @if($card->priority == 'high')
                    <span class="badge bg-danger">High</span>
                  @elseif($card->priority == 'medium')
                    <span class="badge bg-warning text-dark">Medium</span>
                  @else
                    <span class="badge badge-low">Low</span>
                  @endif
                </td>
                <td>
                  @if($card->status == 'todo')
                    <span class="badge bg-secondary">To Do</span>
                  @elseif($card->status == 'in_progress')
                    <span class="badge bg-primary">In Progress</span>
                  @elseif($card->status == 'done')
                    <span class="badge bg-success">Done</span>
                  @endif
                </td>
                <td>
                  @if($card->status == 'todo')
                    <form action="{{ route('developer.cards.start', $card->card_id) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-primary">🚀 Mulai</button>
                    </form>
                  @elseif($card->status == 'in_progress')
                    <form action="{{ route('developer.cards.complete', $card->card_id) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-success">✅ Selesai</button>
                    </form>
                  @else
                    <span class="text-muted fw-bold">✔️ Selesai</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
