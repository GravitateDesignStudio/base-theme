class Accordion {

	constructor(blockEl) {
		this.accordion = blockEl;
        this.itemHeaders = this.accordion.querySelectorAll('.accordion-container__header');
        
        this.setEventHandlers(this.itemHeaders);
	}
    
    setEventHandlers() {
        this.itemHeaders.forEach(accHeader => {
            accHeader.addEventListener('click', () => {
            
            if (accHeader.classList.contains('active')) {
                accHeader.classList.toggle('active');   
            } else {
                this.itemHeaders.forEach(accHeader => {
                    accHeader.classList.remove('active');
                });
                accHeader.classList.add('active');
            }
        });
        });
    }
}

export default Accordion;