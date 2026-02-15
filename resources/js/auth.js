export async function loadUser(role = null) {
    const token = localStorage.getItem('token');

    if (!token) {
        window.location.href = '/login';
        return;
    }

    const res = await fetch('/api/me', {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    });

    if (res.status === 401) {
        localStorage.clear();
        window.location.href = '/login';
        return;
    }

    const user = await res.json();
    localStorage.setItem('user', JSON.stringify(user));

    if (role && user.role !== role) {
        window.location.href = '/login';
        return;
    }

    return user;
}
