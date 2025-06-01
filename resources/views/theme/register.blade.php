<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Register - POS App</title>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
</head>

<body class="bg-primary">
  <div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
      <main>
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-5">
              <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header">
                  <h3 class="text-center font-weight-light my-4">Register</h3>
                </div>
                <div class="card-body">
                  <form method="POST" action="{{ route('register.store') }}">
                    @csrf
                    <div class="form-floating mb-3">
                      <input class="form-control @error('name') is-invalid @enderror" id="name" type="text"
                        name="name" value="{{ old('name') }}" required autofocus placeholder="Full Name" />
                      <label for="name">Full Name</label>
                      @error('name')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <div class="form-floating mb-3">
                      <input class="form-control @error('email') is-invalid @enderror" id="email" type="email"
                        name="email" value="{{ old('email') }}" required placeholder="name@example.com" />
                      <label for="email">Email address</label>
                      @error('email')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <div class="form-floating mb-3">
                      <input class="form-control @error('password') is-invalid @enderror" id="password" type="password"
                        name="password" required placeholder="Password" />
                      <label for="password">Password</label>
                      @error('password')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <div class="form-floating mb-3">
                      <input class="form-control" id="password_confirmation" type="password"
                        name="password_confirmation" required placeholder="Confirm Password" />
                      <label for="password_confirmation">Confirm Password</label>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                      <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
                      <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</body>

</html>
