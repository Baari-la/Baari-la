import { forwardRef, useEffect, useImperativeHandle, useRef } from "react";

export default forwardRef(function TextInput(
    { type = "text", className = "", isFocused = false, ...props },
    ref,
) {
    const localRef = useRef(null);

    useImperativeHandle(ref, () => ({
        focus: () => localRef.current?.focus(),
    }));

    useEffect(() => {
        if (isFocused) {
            localRef.current?.focus();
        }
    }, [isFocused]);

    return (
        <input
            {...props}
            type={type}
            ref={localRef}
            className={`
                rounded-2xl
                border
                border-gray-300
                bg-white
                px-6
                py-4
                font-bold
                text-black
                placeholder:text-gray-200
                shadow-sm
                focus:border-yellow-500
                focus:ring-yellow-500
                ${className}
            `}
        />
    );
});
