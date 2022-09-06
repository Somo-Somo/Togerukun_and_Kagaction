<template>
    <svg
        :style="heightAndWidth"
        viewBox="0 0 50 100"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
    >
        <path
            d="M12.625 0V100"
            stroke="black"
            stroke-width="1.2"
            stroke-dasharray="5 5"
        />
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
