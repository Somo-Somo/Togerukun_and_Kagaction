<template>
    <svg
        :style="heightAndWidth"
        viewBox="0 0 50 100"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
    >
        <g clip-path="url(#clip0_27_19)">
            <path
                d="M12.625 0V100"
                stroke="black"
                stroke-width="1.2"
                stroke-dasharray="5 5"
            />
            <path
                d="M50.5 50.25H12.5"
                stroke="black"
                stroke-width="1.2"
                stroke-dasharray="5 5"
            />
        </g>
        <defs>
            <clipPath id="clip0_27_19">
                <rect width="50" height="100" fill="white" />
            </clipPath>
        </defs>
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
