const StatCard = ({ title, count, color = 'blue' }) => {
  const colorMap = {
    blue: 'bg-blue-100 text-blue-700',
    green: 'bg-green-100 text-green-700',
    purple: 'bg-purple-100 text-purple-700',
  };

  return (
    <div className={`p-4 rounded shadow ${colorMap[color]}`}>
      <h2 className="text-sm font-semibold mb-2">{title}</h2>
      <p className="text-2xl font-bold">{count}</p>
    </div>
  );
};

export default function Dashboard() {
  return (
    <>
      <div className="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <StatCard title="Total Patients" count="326" color="blue" />
        <StatCard title="Appointments Today" count="12" color="green" />
        <StatCard title="Prescriptions" count="78" color="purple" />
      </div>

      <div className="px-6 pb-6">
        <div className="bg-white rounded shadow p-6 h-64 text-gray-500 text-center flex items-center justify-center">
          Graphs, Tables, and Charts Section
        </div>
      </div>
    </>
  );
}
