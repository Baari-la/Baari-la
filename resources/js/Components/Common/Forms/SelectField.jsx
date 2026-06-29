export default function SelectField({
    value,

    onChange,

    options = [],

    className = "",
}) {
    return (
        <select
            value={value}
            onChange={onChange}
            className={`

                rounded-xl

                border

                border-slate-200

                bg-white

                px-4

                py-3

                text-sm

                outline-none

                focus:border-blue-500

                ${className}

            `}
        >
            {options.map((option) => (
                <option key={option.value} value={option.value}>
                    {option.label}
                </option>
            ))}
        </select>
    );
}
