<template>
    <div class="flex flex-col md:flex-row gap-3 pt-3">
        <CardQty entity="interfaceQty" title="Interfaces" />
        <CardQty entity="integratorsQty" title="Integradores" />
    </div>
</template>
<script>
import CardQty from './partials/-card-qty.vue';
export default {
    components: {
        CardQty,
    }
};
</script>
