document.querySelectorAll('.archive-btn').forEach(button => {
	button.addEventListener('click', async () => {
		const projectId = button.dataset.projectId;

		const confirmArchive = confirm('Are you sure you want to archive this project?');

		if (!confirmArchive) {
			return;
		}

		button.disabled = true;
		button.textContent = 'Archiving...';

		const formData = new FormData();
		formData.append('id', projectId);

		try {
			const response = await fetch('index.php?page=ajax_archive_project', {
				method: 'POST',
				body: formData
			});

			const data = await response.json();

			if (!data.ok) {
				showMessage(data.message || 'Could not archive project.', 'error');
				button.disabled = false;
				button.textContent = 'Archive';
				return;
			}

			const card = document.querySelector(`#project-card-${projectId}`);

			if (card) {
				card.classList.add('fade-out');

				setTimeout(() => {
					card.remove();
					checkEmptyProjectList();
				}, 300);
			}

			showMessage('Project archived successfully.', 'success');
		} catch (error) {
			showMessage('AJAX request failed.', 'error');
			button.disabled = false;
			button.textContent = 'Archive';
		}
	});
});

function showMessage(message, type) {
	const box = document.querySelector('#ajax-message');

	if (!box) {
		return;
	}

	box.textContent = message;
	box.className = type === 'success' ? 'ajax-success' : 'ajax-error';

	setTimeout(() => {
		box.textContent = '';
		box.className = '';
	}, 3000);
}

function checkEmptyProjectList() {
	const grid = document.querySelector('#project-grid');
	const existingEmpty = document.querySelector('#empty-state');

	if (!grid) {
		return;
	}

	if (grid.children.length === 0 && !existingEmpty) {
		const empty = document.createElement('div');
		empty.className = 'card';
		empty.id = 'empty-state';
		empty.innerHTML = '<p>No active projects yet.</p>';

		grid.parentNode.insertBefore(empty, grid);
	}
}