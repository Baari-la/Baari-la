export default function TableToolbar({
    left,

    right,
}) {
    return (
        <div className="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>{left}</div>

            <div className="flex items-center gap-3">{right}</div>
        </div>
    );
}
