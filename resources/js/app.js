import './bootstrap';
import 'preline';

document.addEventListener('alpine:initialized', () => {
    window.addEventListener('open-policy-modal', (event) => {
        const modal = Alpine.store('policyModal');
        modal.currentPolicy = event.detail;
        modal.showPolicyModal = true;
    });
});