<template>
    <span v-if="row && row.currency && !row.currency.active">
        {{ formatted }}
    </span>
</template>
<script>
    import { formatNumber } from '@/filters/index';
    export default {
        props:{
            row:{
                default: null
            }
        },
        computed: {
            formatted: function() {
                if (!this.row) {
                    return '';
                }
                if (this.row.rate > 10) {
                    return formatNumber(_.round(this.row.rate))
                }else{
                    return formatNumber(this.row.rate,6)
                }
            }
        },
    }
</script>