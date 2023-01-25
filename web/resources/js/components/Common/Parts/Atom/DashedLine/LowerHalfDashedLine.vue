<template>
    <svg
        :style="heightAndWidth"
        viewBox="0 0 24 28"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
    >
        <path d="M12.06 0V28" stroke="black" stroke-dasharray="5 5" />
    </svg>
</template>

<script>
export default {
    props: {
        heightAndWidth: {
            type: String,
        },
    },
};
</script>
