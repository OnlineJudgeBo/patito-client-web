function getAccessToken() {
    const value = document.cookie.match('(^|;)\\s*accessToken\\s*=\\s*([^;]+)');
    return value ? value.pop() : null;
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.manual-judge-controls').forEach((controls) => {
        controls.addEventListener('click', (event) => event.stopPropagation());
    });
});

async function manuallyJudgeSolution(button) {
    const controls = button?.closest('.manual-judge-controls');
    const select = controls?.querySelector('.manual-verdict-select');
    const solutionId = Number(controls?.dataset.solutionId);
    const token = getAccessToken();

    if (!select || !Number.isInteger(solutionId) || solutionId <= 0 || !token) {
        window.alert('No se pudo iniciar el cambio de veredicto. Vuelve a iniciar sesión.');
        return;
    }

    button.disabled = true;

    try {
        const verdictUrl = `${select.dataset.apiBase}/Judge/solution/${solutionId}/verdict`;
        const body = JSON.stringify({ resultCode: Number(select.value) });
        let response = await fetch(verdictUrl, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body
        });

        if (response.status === 401 && window.PatitoAuth) {
            const freshToken = await window.PatitoAuth.refreshAccessToken();
            if (freshToken) {
                response = await fetch(verdictUrl, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${freshToken}`
                    },
                    body
                });
            }
        }

        if (!response.ok) {
            const error = await response.json().catch(() => null);
            throw new Error(error?.message || 'No se pudo cambiar el veredicto.');
        }

        window.location.reload();
    } catch (error) {
        window.alert(error.message);
        button.disabled = false;
    }
}
