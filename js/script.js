/* Global Scripts - Ocorrências SENAI */

document.addEventListener('DOMContentLoaded', () => {
    // Modal Access Logic
    const accessModal = document.getElementById('accessModal');
    if (accessModal) {
        window.addEventListener('click', (event) => {
            if (event.target == accessModal) {
                closeAccessModal();
            }
        });
    }
});

function openAccessModal() {
    const modal = document.getElementById('accessModal');
    if (!modal) return;

    modal.style.display = 'flex';
    document.getElementById('passwordStep').style.display = 'block';
    document.getElementById('selectionStep').style.display = 'none';
    document.getElementById('viewStep').style.display = 'none';
    document.getElementById('modalTitle').innerText = 'Acesso às Tabelas';
    document.getElementById('accessPassword').value = '';
    document.getElementById('passwordError').style.display = 'none';
    document.body.style.overflow = 'hidden';
}

function closeAccessModal() {
    const modal = document.getElementById('accessModal');
    if (!modal) return;

    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function checkAccessPassword() {
    const password = document.getElementById('accessPassword').value;
    // Senha hardcoded para acesso às tabelas
    if (password === 'senai123') {
        document.getElementById('passwordStep').style.display = 'none';
        document.getElementById('selectionStep').style.display = 'grid';
        document.getElementById('modalTitle').innerText = 'Selecione a Tabela';
    } else {
        document.getElementById('passwordError').style.display = 'block';
    }
}

function viewTable(tableName) {
    const frame = document.getElementById('tableFrame');
    if (!frame) return;

    frame.src = `../tables/${tableName}.php?modal=1`;
    document.getElementById('selectionStep').style.display = 'none';
    document.getElementById('viewStep').style.display = 'block';

    // Capitalize first letter for title
    const nameFormatted = tableName.charAt(0).toUpperCase() + tableName.slice(1);
    document.getElementById('modalTitle').innerText = `Tabela: ${nameFormatted}`;

    // Ajustar width da modal para tabelas maiores
    document.querySelector('.modal-container').style.maxWidth = '1200px';
}

function backToSelection() {
    document.getElementById('viewStep').style.display = 'none';
    document.getElementById('selectionStep').style.display = 'grid';
    document.getElementById('modalTitle').innerText = 'Selecione a Tabela';
    document.querySelector('.modal-container').style.maxWidth = '800px';
}

// REST API Table Actions
function deleteRecord(type, id) {
    if (!confirm(`Tem certeza que deseja excluir este registro de ${type}?`)) return;

    fetch(`../tables/api/${type}.php`, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Registro excluído com sucesso!');
                const frame = document.getElementById('tableFrame');
                if (frame) frame.contentWindow.location.reload();
            } else {
                alert('Erro ao excluir: ' + (data.error || 'Erro desconhecido.'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro de conexão ao tentar excluir.');
        });
}

let currentEditType = '';
let currentEditId = null;

function editRecord(type, id) {
    currentEditType = type;
    currentEditId = id;

    fetch(`../tables/api/${type}.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Erro ao buscar dados: ' + data.error);
                return;
            }

            const fieldsContainer = document.getElementById('editFormFields');
            fieldsContainer.innerHTML = '';

            for (const [key, value] of Object.entries(data)) {
                if (key === 'senha' || key === 'created_at') continue;

                const group = document.createElement('div');
                group.className = 'form-group';

                const label = document.createElement('label');
                label.innerText = key.charAt(0).toUpperCase() + key.slice(1).replace(/_/g, ' ');

                const input = document.createElement('input');
                input.className = 'form-control';
                input.name = key;

                if (key === 'id') {
                    input.type = 'text';
                    input.value = value;
                    input.readOnly = true;
                    group.style.display = 'none';
                } else if (key.includes('data')) {
                    input.type = 'date';
                    if (value) input.value = value.split(' ')[0];
                } else {
                    input.type = 'text';
                    if (value !== null) input.value = value;
                }

                group.appendChild(label);
                group.appendChild(input);
                fieldsContainer.appendChild(group);
            }

            const editModal = document.getElementById('editModal');
            if (editModal) {
                editModal.style.display = 'flex';
                document.getElementById('editModalTitle').innerText = `Editar ${type.charAt(0).toUpperCase() + type.slice(1)}`;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro de conexão ao buscar dados.');
        });
}

function closeEditModal() {
    const editModal = document.getElementById('editModal');
    if (editModal) editModal.style.display = 'none';
}

function submitEditForm(event) {
    event.preventDefault();
    const form = document.getElementById('editForm');
    const formData = new FormData(form);
    const data = {};
    formData.forEach((value, key) => { data[key] = value; });

    fetch(`../tables/api/${currentEditType}.php`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('Registro atualizado com sucesso!');
                closeEditModal();
                const frame = document.getElementById('tableFrame');
                if (frame) frame.contentWindow.location.reload();
            } else {
                alert('Erro ao atualizar: ' + (result.error || 'Erro desconhecido.'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro de conexão ao tentar atualizar.');
        });
}

function submitFormPost(event, type) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const data = {};
    formData.forEach((value, key) => { data[key] = value; });

    // Hide previous alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => alert.style.display = 'none');

    // Create or get dynamic alert container
    let alertContainer = document.getElementById('dynamicAlerts');
    if (!alertContainer) {
        alertContainer = document.createElement('div');
        alertContainer.id = 'dynamicAlerts';
        form.parentNode.insertBefore(alertContainer, form);
    }

    fetch(`../tables/api/${type}.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(result => {
            if (result.success || result.id) {
                alertContainer.innerHTML = '<div class="alert alert-success">Registro cadastrado com sucesso!</div>';
                form.reset();
            } else {
                alertContainer.innerHTML = `<div class="alert alert-error">Erro ao cadastrar: ${result.error || 'Erro desconhecido.'}</div>`;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alertContainer.innerHTML = '<div class="alert alert-error">Erro de conexão com o servidor.</div>';
        });
}
