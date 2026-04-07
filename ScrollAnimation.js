const containers = document.querySelector('.container');
window.addEventListener('scroll', checkContainer);
checkContainer();
function checkContainer(){
	const triggerBottom = window.innerHeight/5*4;
	containers.forEach(container => {
		const containertop = box.getBoundingClientRect().top;
		if(containertop < triggerBottom){
			box.classList.add('show');
		} else {
			container.classList.remove('show');
		}
	});
}