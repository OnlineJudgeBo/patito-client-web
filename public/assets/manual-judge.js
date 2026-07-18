function getAccessToken() {
    const value = document.cookie.match('(^|;)\\s*accessToken\\s*=\\s*([^;]+)');
    return value ? value.pop() : null;
}

async function manuallyJudgeSolution(solutionId) {
    const select = document.getElementById(`manual-verdict-${solutionId}`);
    const token = getAccessToken();

    if (!select || !token) {
        window.alert('No se pudo iniciar el cambio de veredicto. Vuelve a iniciar sesión.');
        return;
    }

    const button = document.getElementById(`manual-verdict-button-${solutionId}`);
    if (button) {
        button.disabled = true;
    }

    try {
        const response = await fetch(`${select.dataset.apiBase}/Judge/solution/${solutionId}/verdict`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ resultCode: Number(select.value) })
        });

        if (!response.ok) {
            const error = await response.json().catch(() => null);
            throw new Error(error?.message || 'No se pudo cambiar el veredicto.');
        }

        window.location.reload();
    } catch (error) {
        window.alert(error.message);
        if (button) {
            button.disabled = false;
        }
    }
}
