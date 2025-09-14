@push('scripts')
<script>
let familleIndex = 1;

function updateNames(container, prefix) {
    container.querySelectorAll('input, textarea').forEach((el, i) => {
        el.name = el.name.replace(/\d+/, i);
    });
}

function makeQuestion(familleIdx, sousFamilleIdx, questionIdx) {
    const div = document.createElement('div');
    div.classList.add('question-item', 'mb-2');
    div.innerHTML = `
        <input type="text" name="familles[${familleIdx}][sous_familles][${sousFamilleIdx}][questions][${questionIdx}][question_text]" class="form-control" placeholder="Question" required>
        <button type="button" class="btn btn-danger btn-sm remove-question mt-1">Supprimer Question</button>
    `;
    return div;
}

document.addEventListener('click', function(e) {
    // Add Question
    if(e.target.matches('.add-question')) {
        const container = e.target.closest('.questions-container');
        const familleIdx = Array.from(container.closest('.famille-item').parentNode.children).indexOf(container.closest('.famille-item'));
        const sousFamilleIdx = Array.from(container.closest('.sous-familles-container').children).indexOf(container.closest('.sous-famille-item'));
        const questionCount = container.querySelectorAll('.question-item').length;
        container.appendChild(makeQuestion(familleIdx, sousFamilleIdx, questionCount));
    }

    // Remove Question
    if(e.target.matches('.remove-question')) {
        e.target.closest('.question-item').remove();
    }

    // Add Sous Famille
    if(e.target.matches('.add-sous-famille')) {
        const container = e.target.closest('.sous-familles-container');
        const familleIdx = Array.from(container.closest('.famille-item').parentNode.children).indexOf(container.closest('.famille-item'));
        const newDiv = container.querySelector('.sous-famille-item').cloneNode(true);
        newDiv.querySelectorAll('input, textarea').forEach(input => input.value = '');
        container.appendChild(newDiv);
    }

    // Remove Sous Famille
    if(e.target.matches('.remove-sous-famille')) {
        e.target.closest('.sous-famille-item').remove();
    }

    // Add Famille
    if(e.target.matches('#add-famille')) {
        const container = document.getElementById('familles-container');
        const newDiv = container.querySelector('.famille-item').cloneNode(true);
        newDiv.querySelectorAll('input, textarea').forEach(input => input.value = '');
        container.appendChild(newDiv);
    }

    // Remove Famille
    if(e.target.matches('.remove-famille')) {
        e.target.closest('.famille-item').remove();
    }
});
</script>
@endpush
