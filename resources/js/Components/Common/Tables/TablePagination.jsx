export default function TablePagination({
    currentPage,

    totalPages,

    onPrevious,

    onNext,
}) {
    return (
        <div className="mt-6 flex items-center justify-between">
            <button
                onClick={onPrevious}
                disabled={currentPage <= 1}
                className="rounded-xl border px-4 py-2"
            >
                Previous
            </button>

            <span className="text-sm text-slate-600">
                Page {currentPage} of {totalPages}
            </span>

            <button
                onClick={onNext}
                disabled={currentPage >= totalPages}
                className="rounded-xl border px-4 py-2"
            >
                Next
            </button>
        </div>
    );
}
