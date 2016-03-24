document.addEventListener('DOMContentLoaded', function() {
	var clickElements = Array.from(document.querySelectorAll('li.archive-micro-year'));
	var blockElements = Array.from(document.querySelectorAll('li.archive-micro-year ul'));

	clickElements.forEach(function(clickElement) {
		clickElement.querySelector('a').addEventListener('click', function() {
			var currentBlock = clickElement.querySelector('ul');

			blockElements.forEach(function(blockElement) {
				if (blockElement === currentBlock && blockElement.style.display !== 'block') {
					blockElement.style.display = 'block';
				} else {
					blockElement.style.display = 'none';
				}
			});
		});
	});
});
